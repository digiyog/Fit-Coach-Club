<?php
   
namespace App\Http\Controllers\AdminPanel;
   
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
   
class FranchiseMembershipPlanController extends Controller
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
     * View Franchise Membership Plans list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyasnh
     * @created_at 19 Jan 2023
     */
    public function index($id=false)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Franchise Membership Plans' => '',
        ];

        // Filter Button
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'filter',
            'btn_text' => __('language.filter'),
            'attributes' => []
        ];

        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.franchise-membership-plans.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        if($id != ''){
            $this->viewData['franchise_id'] = dv($id);
        } else {
            $this->viewData['franchise_id'] = '';
        }

        $franchises         = User::where('status' , 1)->where('role_type' , 'franchise')->get();
        $membershipPlans    = MembershipPlan::where('status' , 1)->get();

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['franchises'] = $franchises;
        $this->viewData['membershipPlans'] = $membershipPlans;

        return view('admin-panel.franchise-membership-plans.index')->with($this->viewData);
    }

    public function getFranchiseMembershipPlans(Request $request){
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "franchise_id" => $request->franchise_id,
            "membership_plan_id" => $request->membership_plan_id,
            "payment_status" => $request->payment_status,
        );

        // Getting Franchise Membership Plans Records
        $records_count = FranchiseMembershipPlan::GetFranchiseMembershipPlans(null, null, $search, $filter, $sort);
        $records = FranchiseMembershipPlan::GetFranchiseMembershipPlans($limit, $start, $search, $filter, $sort);
        
        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $franchise_name = 'N/A';
                $membership_plan_name = 'N/A';
                $payment_status = 'N/A';
                $start_date = '';
                $total_amount = 0;
                $end_date = '';
                $remark = '----';

                // Preparing Data
                if(!empty($value->user_name))
                {
                    $franchise_name = $value->user_name;
                }

                if(!empty($value->membership_plan_name))
                {
                    $membership_plan_name = $value->membership_plan_name;
                }

                if(!empty($value->total_amount))
                {
                    $total_amount = $value->total_amount;
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

                $action = '<a href="' . route('adminPanel.franchise-membership-plans.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                    => $value->id,
                    "franchise_name"        => $franchise_name,
                    "membership_plan_name"  => $membership_plan_name,
                    'total_amount'          => $total_amount,
                    "payment_status"        => $payment_status,
                    "start_date"            => $start_date,
                    "end_date"              => $end_date,
                    "remark"                => $remark,
                    "action"                => $action,
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

    /**
        * View create Franchise Membership Plans.
        *
        * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
        *
        * @author Sandeep
        * @created 20 Jan 2023
    */
    public function create()
    {
        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Franchise Membership Plans' => route('adminPanel.franchise-membership-plans.index'),
            __('language.create') => '',
        ];

        $franchises         = User::where('status' , 1)->where('role_type' , 'franchise')->get();
        $membershipPlans    = MembershipPlan::where('status' , 1)->get();

        // View Data
        $this->viewData['breadcrumb']       = $breadcrumb;
        $this->viewData['franchises']       = $franchises;
        $this->viewData['membershipPlans']  = $membershipPlans;

        return view('admin-panel.franchise-membership-plans.create')->with($this->viewData);
    }

    /**
     * Store Franchise Membership Plans.
     *
     * @return mixed
     *
     * @author Sandeep
     * @created 24 Jan 2023
     */
    public function store(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $franchiseMembershipPlan    = null;
        $errorMessage               = null;
        
        // Begin Transaction
        DB::beginTransaction();
        
        // Create Franchise Membership Plan
        try {

            $membershipPlans = MembershipPlan::where('id' , $request['membership_plan_id'])->first();

            $franchises = User::where('id', $request['franchise_id'])->first();

            $daysToAdd = $membershipPlans['days'];

            $startDate = $franchises->end_date ? Carbon::parse($franchises->end_date) : Carbon::now();
            $startDate = $startDate->addDays(1);

            $endDate = $franchises->end_date ? Carbon::parse($franchises->end_date) : Carbon::now();
            $endDate = $endDate->addDays($daysToAdd);

            if ($franchises) {

                $daysToAdd = $membershipPlans['days']; // jitne din add karne hain

                $currentEndDate = $franchises->end_date ? Carbon::parse($franchises->end_date) : Carbon::now();

                $franchises->end_date = $endDate;
                $franchises->save();
            }

            if($request['payment_status'] == 1){
                $total_amount       = $membershipPlans['price'];
                $received_amount    = 0;
                $pending_amount     = $membershipPlans['price'];
            } else {
                $total_amount       = $membershipPlans['price'];
                $received_amount    = $membershipPlans['price'];
                $pending_amount     = 0;
            }

            // Set data
            $data = [
                'franchise_id'      => $request['franchise_id'],
                'membership_id'     => $request['membership_plan_id'],
                'payment_status'    => $request['payment_status'],
                'remark'            => $request['remark'],
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'total_amount'      => $total_amount,
                'received_amount'   => $received_amount,
                'pending_amount'    => $pending_amount,
            ];
            
            $franchiseMembershipPlan = FranchiseMembershipPlan::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $franchiseMembershipPlan = null;
            $errorMessage   = $e->getMessage();
            \Log::error('FranchiseMembershipPlan create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------
        if (!is_null($franchiseMembershipPlan)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Franchise Membership Plan']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchise-membership-plans.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Franchise Membership Plan']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchise-membership-plans.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Franchise Membership Plan.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function edit(Request $request, $id)
    {
        $franchiseMembershipPlan = FranchiseMembershipPlan::where('id', dv($id))->first();
        
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Franchise Membership Plans' => route('adminPanel.franchise-membership-plans.index'),
            'Edit Franchise Membership Plan' => '',
        ];

        $franchises         = User::where('status' , 1)->where('role_type' , 'franchise')->get();
        $membershipPlans    = MembershipPlan::where('status' , 1)->get();
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['franchiseMembershipPlan'] = $franchiseMembershipPlan;
        $this->viewData['franchises']       = $franchises;
        $this->viewData['membershipPlans']  = $membershipPlans;
        
        return view('admin-panel.franchise-membership-plans.edit')->with($this->viewData);
    }

    /**
     * Update Franchise Membership Plan.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function update(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $franchiseMembershipPlanUpdate  = false;
        $errorMessage = null;
        
        // Update language
        DB::beginTransaction();

        try {

            // Update Franchise Membership Plan
            $franchiseMembershipPlan = FranchiseMembershipPlan::where('id', dv($id))->first();

            if($request['payment_status'] == 1){
                $total_amount       = $franchiseMembershipPlan['total_amount'];
                $received_amount    = 0;
                $pending_amount     = $franchiseMembershipPlan['total_amount'];
            } else {
                $total_amount       = $franchiseMembershipPlan['total_amount'];
                $received_amount    = $franchiseMembershipPlan['total_amount'];
                $pending_amount     = 0;
            }

            // Set data
            $data = [
                'payment_status'    => $request['payment_status'],
                'remark'            => $request['remark'],
                'total_amount'      => $total_amount,
                'received_amount'   => $received_amount,
                'pending_amount'    => $pending_amount,
            ];

            $franchiseMembershipPlanUpdate = FranchiseMembershipPlan::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $franchiseMembershipPlanUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('FranchiseMembershipPlan update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($franchiseMembershipPlanUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Franchise Membership Plan']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchise-membership-plans.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Franchise Membership Plan']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchise-membership-plans.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Change status.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 01 Feb 2023
     */
    public function changeStatus(Request $request)
    {
        $language = FranchiseMembershipPlan::toggleStatus($request['ids']);
        
        // Set response
        if (!is_null($language))
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.status_changed'),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.status_change_failed'),
                '_type' => 'error',
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * Destroy.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created_at 01 Feb 2023
     */
    public function destroy(Request $request)
    {
        $ids = $request['ids'];
        $franchiseMembershipPlan = FranchiseMembershipPlan::whereIn('id', $ids)->delete();
        
        // Set response
        if ($franchiseMembershipPlan == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Franchise Membership Plan']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Franchise Membership Plan']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }
}