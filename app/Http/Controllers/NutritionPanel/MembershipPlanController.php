<?php
   
namespace App\Http\Controllers\NutritionPanel;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\FranchiseMembershipPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
   
class MembershipPlanController extends Controller
{
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
        $this->middleware('auth.admin');
    }

    /**
     * View Membership Plans list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created_at 19 Jan 2023
     */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Membership Plans' => '',
        ];

        // Get membership plans for filter dropdown
        $plans = MembershipPlan::where('status', 1)->orderBy('name', 'asc')->get();

        $summary = FranchiseMembershipPlan::getMembershipPlansSummary(null, ['franchise_id' => $authUser->id]);

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['plans'] = $plans;
        $this->viewData['summary'] = $summary;

        return view('nutrition-panel.membership-plans.index')->with($this->viewData);
    }

    /**
     * Get Membership Plans list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMembershipPlans(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $order = $request->get('order');
        $sort = (!empty($order) && is_array($order)) ? $order[0] : null;

        $searchVal = $request->get('search');
        $search = is_array($searchVal) ? ($searchVal['value'] ?? null) : null;
        if (empty($search) && !empty($request->search_query)) {
            $search = $request->search_query;
        }

        // Filter Parameters
        $filter = array(
            "franchise_id"       => $authUser->id,
            "membership_plan_id" => $request->membership_plan_id,
            "payment_status"     => $request->payment_status,
            "amount_type"        => $request->amount_type,
            "filter_date_range"  => $request->filter_date_range,
        );

        // Getting Membership Plans Records
        $records_count = FranchiseMembershipPlan::GetMembershipPlans(null, null, $search, $filter, $sort);
        $records = FranchiseMembershipPlan::GetMembershipPlans($limit, $start, $search, $filter, $sort);
        $summary = FranchiseMembershipPlan::GetMembershipPlansSummary($search, $filter);
        
        $arr_data = array();
        if(!empty($records) && count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $membership_plan_name = !empty($value->membership_plan_name) ? e($value->membership_plan_name) : 'N/A';
                
                $totalAmountVal = floatval($value->total_amount ?? 0);
                if ($totalAmountVal > 0) {
                    $total_amount = '<span class="plan-amount-paid">' . number_format($totalAmountVal, 0, '', '') . '</span>';
                } else {
                    $total_amount = '<span class="plan-amount-zero">0</span>';
                }

                if($value->payment_status == 1){
                    $payment_status = '<span class="badge-plan-status status-pending"><i class="fa fa-clock-o"></i> Pending</span>';
                } else {
                    $payment_status = '<span class="badge-plan-status status-completed"><i class="fa fa-check-circle"></i> Completed</span>';
                }

                $start_date = !empty($value->start_date) ? Carbon::parse($value->start_date)->format('d-m-Y') : 'N/A';
                $end_date = !empty($value->end_date) ? Carbon::parse($value->end_date)->format('d-m-Y') : 'N/A';

                if(!empty($value->remark))
                {   
                    $remark = '<span class="plan-remark-bold">' . e($value->remark) . '</span>';
                } else {
                    $remark = '<span class="plan-remark-dash">----</span>';
                }

                // Array Data
                $arr_data[] = array(
                    "membership_plan_name"  => '<span class="plan-name-text">' . $membership_plan_name . '</span>',
                    "total_amount"          => $total_amount,
                    "payment_status"        => $payment_status,
                    "start_date"            => '<span class="plan-date-text">' . $start_date . '</span>',
                    "end_date"              => '<span class="plan-date-text">' . $end_date . '</span>',
                    "remark"                => $remark,
                );
            }
        }
        $totalRecords = $records_count;

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $arr_data,
            "summary"              => $summary,
        );

        return response()->json($response);
    }
}