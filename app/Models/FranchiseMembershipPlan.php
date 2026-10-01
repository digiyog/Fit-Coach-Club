<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FranchiseMembershipPlan extends Model
{
    protected $table = "franchise_memberships";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Common filter helper
     */
    public static function applyMembershipFilters($query, $search = null, $filter = array())
    {
        if (isset($filter['franchise_id']) && !empty($filter['franchise_id'])) {
            $query->where('franchise_memberships.franchise_id', $filter['franchise_id']);
        }

        if (isset($filter['membership_plan_id']) && !empty($filter['membership_plan_id'])) {
            $query->where('franchise_memberships.membership_id', $filter['membership_plan_id']);
        }

        if (isset($filter['payment_status']) && !empty($filter['payment_status'])) {
            $query->where('franchise_memberships.payment_status', $filter['payment_status']);
        }

        if (isset($filter['amount_type']) && !empty($filter['amount_type'])) {
            if ($filter['amount_type'] === 'paid') {
                $query->where('franchise_memberships.total_amount', '>', 0);
            } elseif ($filter['amount_type'] === 'zero') {
                $query->where('franchise_memberships.total_amount', '<=', 0);
            }
        }

        if (isset($filter['filter_date_range']) && !empty($filter['filter_date_range'])) {
            $rangeStr = $filter['filter_date_range'];
            if (strpos($rangeStr, 'to') !== false) {
                $dateRange = explode('to', $rangeStr);
            } elseif (strpos($rangeStr, '/') !== false) {
                $dateRange = explode('/', $rangeStr);
            } elseif (strpos($rangeStr, ' - ') !== false) {
                $dateRange = explode(' - ', $rangeStr);
            } else {
                $dateRange = [];
            }

            if (count($dateRange) == 2) {
                $query->whereDate('franchise_memberships.start_date', '>=', Carbon::parse(trim($dateRange[0])));
                $query->whereDate('franchise_memberships.start_date', '<=', Carbon::parse(trim($dateRange[1])));
            }
        }

        if (!empty($search)) {
            $search = strtolower(trim($search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('lower(membership_plans.name) LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('lower(franchise_memberships.remark) LIKE ?', ['%' . $search . '%']);
            });
        }

        return $query;
    }

    // Get Franchise Membership Plans list records (Admin Panel)
    public function scopeGetFranchiseMembershipPlans($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $franchiseMembershipPlans = FranchiseMembershipPlan::select('franchise_memberships.id', 'franchise_memberships.franchise_id', 'franchise_memberships.membership_id', 'franchise_memberships.total_amount', 'franchise_memberships.payment_status', 'franchise_memberships.start_date', 'franchise_memberships.end_date', 'franchise_memberships.remark', 'users.name as user_name', 'membership_plans.name as membership_plan_name');

        $franchiseMembershipPlans->Join("users", function ($join) {
            $join->on("franchise_memberships.franchise_id", "=", "users.id");
        });

        $franchiseMembershipPlans->Join("membership_plans", function ($join) {
            $join->on("franchise_memberships.membership_id", "=", "membership_plans.id");
        });

        // Record filter conditions
        $franchiseMembershipPlans->where(function ($query) use ($filter) {
            if(isset($filter['franchise_id']) && !(empty($filter['franchise_id'])))
            {
                $query->where('franchise_memberships.franchise_id', $filter['franchise_id']);
            }

            if(isset($filter['membership_plan_id']) && !(empty($filter['membership_plan_id'])))
            {
                $query->where('franchise_memberships.membership_id', $filter['membership_plan_id']);
            }

            if(isset($filter['payment_status']) && !(empty($filter['payment_status'])))
            {
                $query->where('franchise_memberships.payment_status', $filter['payment_status']);
            }
        });

        // Table list Search conditions
        if (!(empty($search))) {
            $search = strtolower($search);
            $franchiseMembershipPlans = $franchiseMembershipPlans->whereRaw('(lower(users.name) LIKE \'%' . $search . '%\' || lower(membership_plans.name) LIKE \'%' . $search . '%\' )');
        }

        // Table columns sort conditions
        if ($sort && isset($sort['column']) && $sort['column'] > 0) {
            $arr_fields = array("", "", "", 'total_amount', 'payment_status', "start_date", "end_date", "remark", "");

            for ($field = 0; $field < count($arr_fields); $field++) {
                if ($sort['column'] == $field && $arr_fields[$field] != "") {
                    $franchiseMembershipPlans = $franchiseMembershipPlans->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }

        // Set final limit and records
        if (!empty($limit)) {
            $franchiseMembershipPlans = $franchiseMembershipPlans->skip($offset)->take($limit);
            return $franchiseMembershipPlans->orderBy('id', 'desc')->get();
        } else {
            return $franchiseMembershipPlans->get()->count();
        }
    }

    // Get Membership Plans list records (Nutrition Panel)
    public function scopeGetMembershipPlans($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $membershipPlans = FranchiseMembershipPlan::select('franchise_memberships.id', 'franchise_memberships.franchise_id', 'franchise_memberships.membership_id', 'franchise_memberships.total_amount', 'franchise_memberships.payment_status', 'franchise_memberships.start_date', 'franchise_memberships.end_date', 'franchise_memberships.remark', 'membership_plans.name as membership_plan_name');

        $membershipPlans->Join("membership_plans", function ($join) {
            $join->on("franchise_memberships.membership_id", "=", "membership_plans.id");
        });

        self::applyMembershipFilters($membershipPlans, $search, $filter);

        // Sorting
        // Columns: 0: membership_plan_name, 1: total_amount, 2: payment_status, 3: start_date, 4: end_date, 5: remark
        if (!empty($sort) && isset($sort['column'])) {
            $arr_fields = array("membership_plans.name", "franchise_memberships.total_amount", "franchise_memberships.payment_status", "franchise_memberships.start_date", "franchise_memberships.end_date", "franchise_memberships.remark");
            $colIdx = intval($sort['column']);
            if (isset($arr_fields[$colIdx]) && !empty($arr_fields[$colIdx])) {
                $dir = $sort['dir'] ?? 'DESC';
                $membershipPlans->orderBy($arr_fields[$colIdx], $dir);
            } else {
                $membershipPlans->orderBy('franchise_memberships.start_date', 'DESC')->orderBy('franchise_memberships.id', 'DESC');
            }
        } else {
            $membershipPlans->orderBy('franchise_memberships.start_date', 'DESC')->orderBy('franchise_memberships.id', 'DESC');
        }

        // Set final limit and records
        if (!empty($limit)) {
            $membershipPlans = $membershipPlans->skip($offset)->take($limit);
            return $membershipPlans->get();
        } else {
            return $membershipPlans->get()->count();
        }
    }

    /**
     * Get Membership Plans Summary for KPI Card
     */
    public function scopeGetMembershipPlansSummary($model, $search = null, $filter = array())
    {
        $query = FranchiseMembershipPlan::Join("membership_plans", function ($join) {
            $join->on("franchise_memberships.membership_id", "=", "membership_plans.id");
        });

        self::applyMembershipFilters($query, $search, $filter);

        $totalRecords = (clone $query)->count('franchise_memberships.id');
        $recordedAmount = (float) (clone $query)->sum('franchise_memberships.total_amount');
        $zeroAmountPlans = (clone $query)->where('franchise_memberships.total_amount', '<=', 0)->count();
        $completedPayments = (clone $query)->where('franchise_memberships.payment_status', 2)->count();

        // Get plan breakdown
        $planBreakdown = (clone $query)
            ->select('membership_plans.name as plan_name', DB::raw('count(franchise_memberships.id) as count'))
            ->groupBy('membership_plans.name')
            ->orderBy('count', 'desc')
            ->get();

        $colors = ['#2563eb', '#c084fc', '#10b981', '#f59e0b', '#06b6d4', '#ec4899'];
        $breakdownData = [];
        foreach ($planBreakdown as $idx => $item) {
            $pct = $totalRecords > 0 ? round(($item->count / $totalRecords) * 100, 1) : 0;
            $breakdownData[] = [
                'name' => $item->plan_name,
                'count' => $item->count,
                'percent' => $pct,
                'color' => $colors[$idx % count($colors)],
            ];
        }

        return [
            'total_records' => $totalRecords,
            'recorded_amount' => $recordedAmount,
            'recorded_amount_formatted' => number_format($recordedAmount, 0),
            'zero_amount_plans' => $zeroAmountPlans,
            'completed_payments' => $completedPayments,
            'completion_text' => $completedPayments . ' / ' . $totalRecords,
            'breakdown' => $breakdownData,
        ];
    }
}
