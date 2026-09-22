<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Activity;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ActivityController extends Controller
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
     * View Activities list.
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
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Activities' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.activities.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // Calculate Activity Statistics
        $totalActivities = Activity::where('created_by', $authUser['id'])->count();
        $scheduledActivities = Activity::where('created_by', $authUser['id'])->where('activity_type', 2)->count();
        $publishedActivities = Activity::where('created_by', $authUser['id'])->where('status', 1)->count();
        $notificationsSent = Activity::where('created_by', $authUser['id'])->where('status', 1)->count();

        // View Data
        $this->viewData['totalActivities'] = $totalActivities;
        $this->viewData['scheduledActivities'] = $scheduledActivities;
        $this->viewData['publishedActivities'] = $publishedActivities;
        $this->viewData['notificationsSent'] = $notificationsSent;
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.activities.index')->with($this->viewData);
    }

    /**
     * Get Activities list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getActivities(Request $request)
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
            'activity_type' => $request->get('activity_type'),
            'date_filter'   => $request->get('date_filter'),
            'status'        => $request->get('status'),
        );

        // Getting Activities Records
        $records_count  = Activity::getActivities(null, null, $search, $filter, $sort);
        $records        = Activity::getActivities($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $activity_type      = 'N/A';
                $date               = 'N/A';
                $app_notification   = '<span class="act-badge-notify"><i class="fa fa-bell-o"></i> Ready</span>';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = '<span class="fw-bold text-dark">' . e($value->name) . '</span>';
                }

                if($value->activity_type == 1){
                    $activity_type = '<span class="act-badge-type type-old">Old Activity</span>';
                } else if($value->activity_type == 2){
                    $activity_type = '<span class="act-badge-type type-upcoming">Upcoming Activity</span>';
                }

                if(!empty($value->date))
                {
                    $date = '<span class="act-date-text"><i class="fa fa-calendar-o text-muted me-1"></i> ' . date("d M Y", strtotime($value->date)) . '</span>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1 act-order-input text-center" id="activity_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status = '<span class="act-table-status act-status-inactive badge badge-warning">Inactive</span>';
                } else {
                    $status = '<span class="act-table-status act-status-active badge badge-success">Active</span>';
                }

                $action = '<a href="' . route('nutritionPanel.activities.edit', ['id' => ev($value->id)]) . '" class="act-table-action-edit" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "activity_type"     => $activity_type,
                    "date"              => $date,
                    "app_notification"  => $app_notification,
                    "order"             => $order,
                    "status"            => $status,
                    "action"            => $action,
                );
            }
        }

        $totalRecords = $records_count;
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
        * View create Activities.
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
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Activities' => route('nutritionPanel.activities.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('nutrition-panel.activities.create')->with($this->viewData);
    }

    /**
     * Store Activities.
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
 
        $activity       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Activity
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'activity_type'         => $request['activity_type'],
                'date'                  => date("Y-m-d", strtotime($request['date'])),
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Activity image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.activities.image_path'), null, 'activities-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $activity = Activity::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $activity       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('Activity create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($activity)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Activity']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.activities.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Activity']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.activities.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Activities.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $activity = Activity::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Activities' => route('nutritionPanel.activities.index'),
            __('language.edit') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['activity'] = $activity;
        
        return view('nutrition-panel.activities.edit')->with($this->viewData);
    }

    /**
     * Update Activity.
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
        
        $activityUpdate  = false;
        $errorMessage       = null;
        
        // Update Activity
        DB::beginTransaction();

        try {

            // Update Activity
            $activity = Activity::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'activity_type'         => $request['activity_type'],
                'date'                  => date("Y-m-d", strtotime($request['date'])),
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Activity image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($activity->image)) {
                    delete_image(config('constants.activities.image_path'), $activity->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.activities.image_path'), null, 'activities-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $activityUpdate = Activity::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $activityUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Activity update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($activityUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Activity']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.activities.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Activity']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.activities.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = Activity::toggleStatus($request['ids']);
        
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
        $activity = Activity::whereIn('id', $ids)->delete();
        
        // Set response
        if ($activity == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Activity']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Activity']),
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

            Activity::find($value[0])->update($data);
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
