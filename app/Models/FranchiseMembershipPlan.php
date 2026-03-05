<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseMembershipPlan extends Model
{
    protected $table = "franchise_memberships";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Get Franchise Membership Plans list records
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
            // Filter
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
        if ($sort['column'] > 0) {
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

    // Get Membership Plans list records
    public function scopeGetMembershipPlans($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $membershipPlans = FranchiseMemberShipPlan::select('franchise_memberships.id', 'franchise_memberships.franchise_id', 'franchise_memberships.membership_id', 'franchise_memberships.total_amount', 'franchise_memberships.payment_status', 'franchise_memberships.start_date', 'franchise_memberships.end_date', 'franchise_memberships.remark', 'membership_plans.name as membership_plan_name');

        $membershipPlans->Join("membership_plans", function ($join) {
            $join->on("franchise_memberships.membership_id", "=", "membership_plans.id");
        });

        // Record filter conditions
        $membershipPlans->where(function ($query) use ($filter) {
            // Filter
            if(isset($filter['franchise_id']) && !(empty($filter['franchise_id'])))
            {
                $query->where('franchise_memberships.franchise_id', $filter['franchise_id']);
            }
        });

        // Table list Search conditions
        if (!(empty($search))) {
            $search = strtolower($search);
            $membershipPlans = $membershipPlans->whereRaw('(lower(membership_plans.name) LIKE \'%' . $search . '%\' )');
        }

        // Table columns sort conditions
        if ($sort['column'] > 0) {
            $arr_fields = array("", "", 'payment_status', "start_date", "end_date", "remark");

            for ($field = 0; $field < count($arr_fields); $field++) {
                if ($sort['column'] == $field && $arr_fields[$field] != "") {
                    $membershipPlans = $membershipPlans->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }

        // Set final limit and records
        if (!empty($limit)) {
            $membershipPlans = $membershipPlans->skip($offset)->take($limit);
            return $membershipPlans->orderBy('id', 'desc')->get();
        } else {
            return $membershipPlans->get()->count();
        }
    }
}
