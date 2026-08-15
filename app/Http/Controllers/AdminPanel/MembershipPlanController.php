<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\MembershipPlan;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class MembershipPlanController extends Controller
{
    use UploadImage;

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
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Membership Plans' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.membership-plans.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('admin-panel.membership-plans.index')->with($this->viewData);
    }

    /**
     * Get Membership Plans list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getMembershipPlans(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $sort   = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
        );

        // Getting Membership Plans Records
        $records_count  = MembershipPlan::getMembershipPlans(null, null, $search, $filter, $sort);
        $records        = MembershipPlan::getMembershipPlans($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $price              = 'N/A';
                $days               = 'N/A';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->price)){
                    $price = $value->price;
                }

                if(!empty($value->days)){
                    $days = $value->days;
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="membership_plan_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.membership-plans.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "price"             => $price,
                    "days"              => $days,
                    "order"             => $order,
                    "status"            => $status,
                    "action"            => $action,
                );
            }
        }

        $totalRecords       = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return json_encode($response);
    }

    /**
        * View create Membership Plans.
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
            'Membership Plans' => route('adminPanel.membership-plans.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('admin-panel.membership-plans.create')->with($this->viewData);
    }

    /**
     * Store Membership Plans.
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
 
        $membershipPlan = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Membership Plan
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'price'                 => $request['price'],
                'days'                  => $request['days'],
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];
            
            $membershipPlan = MembershipPlan::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $membershipPlan = null;
            $errorMessage   = $e->getMessage();
            \Log::error('MembershipPlan create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($membershipPlan)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Membership Plan']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.membership-plans.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Membership Plan']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.membership-plans.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Membership Plans.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $membershipPlan = MembershipPlan::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Membership Plans' => route('adminPanel.membership-plans.index'),
            __('language.edit') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['membershipPlan'] = $membershipPlan;

        return view('admin-panel.membership-plans.edit')->with($this->viewData);
    }

    /**
     * Update Membership Plan.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function update(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $membershipPlanUpdate   = false;
        $errorMessage           = null;
        
        // Update Membership Plan
        DB::beginTransaction();

        try {
            // Update Membership Plan
            $membershipPlan = MembershipPlan::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'price'                 => $request['price'],
                'days'                  => $request['days'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];
            
            $membershipPlanUpdate = MembershipPlan::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $membershipPlanUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('MembershipPlan update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($membershipPlanUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Membership Plan']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.membership-plans.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Membership Plan']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.membership-plans.edit')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Change status.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 24 Jan 2023
    */
    public function changeStatus(Request $request)
    {
        $language = MembershipPlan::toggleStatus($request['ids']);
        
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
     * @created_at 19 Jan 2023
     */
    public function destroy(Request $request)
    {
        $ids = $request['ids'];
        $membershipPlan = MembershipPlan::whereIn('id', $ids)->delete();
        
        // Set response
        if ($membershipPlan == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Membership Plan']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Membership Plan']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * Update Order.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 13 Feb 2023
     */
    public function updateOrder(Request $request)
    {
        foreach ($request['ids'] as $key => $value) {

            // Set data
            $data = [
                'order' => $value[1],
            ];
            //---------

            MembershipPlan::find($value[0])->update($data);
        }

        // Set response
        $response = [
            '_status' => true,
            '_message' => 'Order changed successfully.',
            '_type' => 'success',
        ];
        //-------------

        return response()->json($response, 200);
    }

}
