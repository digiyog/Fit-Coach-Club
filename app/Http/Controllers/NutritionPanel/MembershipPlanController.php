<?php
   
namespace App\Http\Controllers\NutritionPanel;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\FranchiseMembershipPlan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\UploadImage;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
   
class MembershipPlanController extends Controller
{
    use UploadImage, UploadFile;

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
     * @author Divyasnh
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

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;

        return view('nutrition-panel.membership-plans.index')->with($this->viewData);
    }

    public function getMembershipPlans(Request $request){
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "franchise_id" => $authUser->id,
        );

        // Getting Membership Plans Records
        $records_count = FranchiseMembershipPlan::GetMembershipPlans(null, null, $search, $filter, $sort);
        $records = FranchiseMembershipPlan::GetMembershipPlans($limit, $start, $search, $filter, $sort);
        
        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $membership_plan_name = 'N/A';
                $payment_status = 'N/A';
                $total_amount = 'N/A';
                $start_date = '';
                $end_date = '';
                $remark = '----';

                // Preparing Data
                if(!empty($value->membership_plan_name))
                {
                    $membership_plan_name = $value->membership_plan_name;
                }

                if($value['payment_status'] == 1){
                    $total_amount = '<span class="text-danger">'.$value['total_amount'].'</span>';
                } else {
                    $total_amount = '<span class="text-success">'.$value['total_amount'].'</span>';
                }

                if($value['payment_status'] == 1){
                    $payment_status = '<label class="badge badge-danger">Pending</label>';
                } else {
                    $payment_status = '<label class="badge badge-success">Completed</label>';
                }

                if(!empty($value->start_date))
                {
                    $start_date = date("d-m-Y", strtotime($value->start_date));
                }

                if(!empty($value->end_date))
                {
                    $end_date = date("d-m-Y", strtotime($value->end_date));
                }

                if(!empty($value->remark))
                {   
                    $remark = $value->remark;
                }

                // Array Data
                $arr_data[] = array(
                    "id"                    => $value->id,
                    "membership_plan_name"  => $membership_plan_name,
                    "total_amount"          => $total_amount,
                    "payment_status"        => $payment_status,
                    "start_date"            => $start_date,
                    "end_date"              => $end_date,
                    "remark"                => $remark,
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );

        return json_encode($response);
    }
}