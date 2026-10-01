<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\DishType;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class DishTypeController extends Controller
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
     * View Dish Types list.
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
            'Dish Types' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.dish-types.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // Statistics
        $totalDishTypes = DishType::where('created_by', $authUser->id)->count();
        $publishedDishTypes = DishType::where('created_by', $authUser->id)->where('status', 1)->count();
        $hiddenDishTypes = DishType::where('created_by', $authUser->id)->where('status', 0)->count();
        $draftDishTypes = 0;

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['totalDishTypes'] = $totalDishTypes;
        $this->viewData['publishedDishTypes'] = $publishedDishTypes;
        $this->viewData['hiddenDishTypes'] = $hiddenDishTypes;
        $this->viewData['draftDishTypes'] = $draftDishTypes;
        
        return view('nutrition-panel.dish-types.index')->with($this->viewData);
    }

    /**
     * Get Dish Types list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getDishTypes(Request $request)
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

        // Getting Dish Types Records
        $records_count  = DishType::getDishTypes(null, null, $search, $filter, $sort);
        $records        = DishType::getDishTypes($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $visibility         = '';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = '<span class="fw-bold text-dark">' . e($value->name) . '</span>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control text-center numeric dish-type-order-input" id="dish_type_order_'.$value->id.'" name="order" value="'.$value->order.'" style="max-width: 75px; height: 34px; border-radius: 8px; font-weight: 600;" autocomplete="off" />';
                }

                if ( $value->status == 1 ){
                    $visibility = '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill"><i class="fa fa-check-circle me-1"></i> Published</span>';
                    $status = '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Active</span>';
                } else {
                    $visibility = '<span class="badge bg-light text-muted border px-2 py-1 rounded-pill"><i class="fa fa-eye-slash me-1"></i> Hidden</span>';
                    $status = '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Inactive</span>';
                }

                $action = '<a href="' . route('nutritionPanel.dish-types.edit', ['id' => ev($value->id)]) . '" class="btn btn-sm btn-outline-primary px-2 py-1 rounded-2 d-inline-flex align-items-center gap-1" title="Edit"><i class="fa fa-pencil"></i> <span>Edit</span></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "visibility"        => $visibility,
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
        * View create Dish Types.
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
            'Dish Types' => route('nutritionPanel.dish-types.index'),
            __('language.create') => '',
        ];

        $dishTypes = DishType::where('status',1)->orderBy('id', 'DESC')->get();

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['dishTypes']  = $dishTypes;

        return view('nutrition-panel.dish-types.create')->with($this->viewData);
    }

    /**
     * Store Dish Types.
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
 
        $dishType       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Dish Type
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Dish Type image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.dish-types.image_path'), null, 'dish-types-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $dishType = DishType::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $dishType       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('DishType create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($dishType)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Dish Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.dish-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Dish Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.dish-types.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Dish Types.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $dishType = DishType::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Dish Types' => route('nutritionPanel.dish-types.index'),
            __('language.edit') => '',
        ];

        $dishTypes = DishType::where('status',1)->orderBy('id', 'DESC')->get();
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['dishType'] = $dishType;
        $this->viewData['dishTypes']  = $dishTypes;

        return view('nutrition-panel.dish-types.edit')->with($this->viewData);
    }

    /**
     * Update Dish Type.
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
        
        $dishTypeUpdate  = false;
        $errorMessage       = null;
        
        // Update Dish Type
        DB::beginTransaction();

        try {

            // Update Dish Type
            $dishType = DishType::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Dish Type image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($dishType->image)) {
                    delete_image(config('constants.dish-types.image_path'), $dishType->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.dish-types.image_path'), null, 'dish-types-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $dishTypeUpdate = DishType::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $dishTypeUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('DishType update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($dishTypeUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Dish Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.dish-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Dish Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.dish-types.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = DishType::toggleStatus($request['ids']);
        
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
        $dishType = DishType::whereIn('id', $ids)->delete();
        
        // Set response
        if ($dishType == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Dish Type']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Dish Type']),
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

            DishType::find($value[0])->update($data);
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
