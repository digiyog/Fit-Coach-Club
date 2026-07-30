<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\MealType;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class MealTypeController extends Controller
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
     * View Meal Types list.
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
            'Meal Types' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.meal-types.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['id'] = $id;
        
        return view('admin-panel.meal-types.index')->with($this->viewData);
    }

    /**
     * Get Meal Types list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getMealTypes(Request $request)
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

        // Getting Meal Types Records
        $records_count  = MealType::getMealTypes(null, null, $search, $filter, $sort);
        $records        = MealType::getMealTypes($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="meal_type_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.meal-types.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
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
        * View create Meal Types.
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
            'Meal Types' => route('adminPanel.meal-types.index'),
            __('language.create') => '',
        ];

        $weekdays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];

        $scheduleTypes = config('constants.schedule_types');

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['weekdays'] = $weekdays;
        $this->viewData['scheduleTypes'] = $scheduleTypes;

        return view('admin-panel.meal-types.create')->with($this->viewData);
    }

    /**
     * Store Meal Types.
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
 
        $mealType       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Meal Type
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'order'                 => $request['order'],
                'description'           => json_encode($request['meals']),
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Meal Type image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.meal-types.image_path'), null, 'meal-types-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $mealType = MealType::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $mealType       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('MealType create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($mealType)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Meal Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.meal-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Meal Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.meal-types.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Meal Types.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $mealType = MealType::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Meal Types' => route('adminPanel.meal-types.index'),
            __('language.edit') => '',
        ];

        $weekdays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];

        $scheduleTypes = config('constants.schedule_types');

        // description is JSON / array
        $mealData = json_decode($mealType->description, true) ?? [];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['weekdays'] = $weekdays;
        $this->viewData['scheduleTypes'] = $scheduleTypes;
        $this->viewData['mealType'] = $mealType;
        $this->viewData['mealData'] = $mealData;

        return view('admin-panel.meal-types.edit')->with($this->viewData);
    }

    /**
     * Update Meal Type.
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
        
        $mealTypeUpdate  = false;
        $errorMessage       = null;
        
        // Update Meal Type
        DB::beginTransaction();

        try {

            // Update Meal Type
            $mealType = MealType::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'order'                 => $request['order'],
                'description'           => json_encode($request['meals']),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Meal Type image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($mealType->image)) {
                    delete_image(config('constants.meal-types.image_path'), $mealType->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.meal-types.image_path'), null, 'meal-types-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $mealTypeUpdate = MealType::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $mealTypeUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('MealType update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($mealTypeUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Meal Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.meal-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Meal Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.meal-types.edit')->withInput()->with(['notification' => $notification]);
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
        $language = MealType::toggleStatus($request['ids']);
        
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
        $mealType = MealType::whereIn('id', $ids)->delete();
        
        // Set response
        if ($mealType == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Meal Type']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Meal Type']),
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

            MealType::find($value[0])->update($data);
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
