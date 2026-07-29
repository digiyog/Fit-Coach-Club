<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Storage;
use Carbon\Carbon;
use App\Http\Traits\SendPushNotification;
use App\Models\CompanyProfile;
use App\Models\FranchiseMembershipPlan;
use Illuminate\Support\Facades\DB;
use Nexmo\Laravel\Facade\Nexmo;

class DashboardController extends Controller
{
    use SendPushNotification;

    /**
     * @var array
     */
    public $viewData = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin', ['except' => ['sendTestNotification']]);
    }

    /**
     * View dashboard.
     *
     * @summary dashboard.
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * @author Divyansh
     * @created 13 Feb 2023
     */
    public function index(Request $request)
    {
        // Get users
        $authUser = auth()->user();
        //----------

        $companyProfile = CompanyProfile::where('id',1)->get();
        $totalFranchise = User::where('role_type','franchise')->count();
        $totalAmount    = FranchiseMembershipPlan::sum('total_amount');
        $receivedAmount = FranchiseMembershipPlan::sum('received_amount');
        $pendingAmount  = FranchiseMembershipPlan::sum('pending_amount');

        $franchises = User::select('users.id', 'users.name', 'users.email' ,'users.mobile_number', 'users.status', 'users.end_date', 'users.start_date', 'users.created_at')->where("role_type", 'franchise')
        ->orderBy('users.id', 'DESC')->get();

        $franchiseMembershipPlans = FranchiseMembershipPlan::select('franchise_memberships.id', 'franchise_memberships.franchise_id', 'franchise_memberships.membership_id', 'franchise_memberships.total_amount', 'franchise_memberships.payment_status', 'franchise_memberships.start_date', 'franchise_memberships.end_date', 'franchise_memberships.remark', 'users.name as user_name', 'membership_plans.name as membership_plan_name')
        ->Join("users", function ($join) {
            $join->on("franchise_memberships.franchise_id", "=", "users.id");
        })
        ->Join("membership_plans", function ($join) {
            $join->on("franchise_memberships.membership_id", "=", "membership_plans.id");
        })
        ->where('payment_status', 1)
        ->orderBy('id', 'desc')->get();

        $top10ActiveThisMonth = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.end_date',
            'users.start_date',
            'users.created_at'
        )
        ->where('role_type', 'franchise')
        ->whereDate('end_date', '>=', now()) // ✅ active
        ->withSum([
            'franchise_memberships as total_amount_sum' => function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year)
                  ->where('payment_status', 1);
            }
        ], 'total_amount')
        ->orderByDesc('total_amount_sum')
        ->limit(10)
        ->get();

        $top10InActiveThisMonth = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.end_date',
            'users.start_date',
            'users.created_at'
        )
        ->where('role_type', 'franchise')
        ->whereDate('end_date', '<', now()) // ❌ inactive
        ->withSum([
            'franchise_memberships as total_amount_sum' => function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year)
                  ->where('payment_status', 1);
            }
        ], 'total_amount')
        ->orderByDesc('users.id')
        ->limit(10)
        ->get();

        $franchiseThisMonth = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.end_date',
            'users.start_date',
            'users.created_at'
        )
        ->where('role_type', 'franchise')
        ->whereMonth('created_at', '=', now()->month)->whereYear('created_at', '=', now()->year)
        ->orderByDesc('users.id')
        ->get();

        $platformUsageThisMonth = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.end_date',
            'users.start_date',
            'users.created_at'
        )
        ->where('role_type', 'franchise')
        ->whereDate('end_date', '>=', now()) // ✅ active
        ->orderByDesc('users.id')
        ->get();

        $franchiseLifeCycle = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.end_date',
            'users.start_date',
            'users.created_at'
        )
        ->where('role_type', 'franchise')
        ->orderByDesc('users.id')
        ->get();

        $breadcrumb = [
            __('language.dashboard_menu') => ''
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['companyProfile'] = $companyProfile;
        $this->viewData['totalFranchise'] = $totalFranchise;
        $this->viewData['totalAmount'] = $totalAmount;
        $this->viewData['receivedAmount'] = $receivedAmount;
        $this->viewData['pendingAmount'] = $pendingAmount;
        $this->viewData['franchiseMembershipPlans'] = $franchiseMembershipPlans;
        $this->viewData['franchises'] = $franchises;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['top10ActiveThisMonths'] = $top10ActiveThisMonth;
        $this->viewData['top10InActiveThisMonths'] = $top10InActiveThisMonth;
        $this->viewData['franchiseThisMonths'] = $franchiseThisMonth;
        $this->viewData['platformUsageThisMonths'] = $platformUsageThisMonth;
        $this->viewData['franchiseLifeCycles'] = $franchiseLifeCycle;

        return view('admin-panel.dashboard.index')->with($this->viewData);
    }
}
