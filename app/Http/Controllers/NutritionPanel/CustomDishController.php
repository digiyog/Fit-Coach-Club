<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\DishType;
use App\Models\CustomDish;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CustomDishController extends Controller
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
     * View Custom Dishes list.
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
            'Custom Dishes' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.custom-dishes.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['id'] = $id;
        
        return view('nutrition-panel.custom-dishes.index')->with($this->viewData);
    }

    /**
     * Get Custom Dishes list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getCustomDishes(Request $request)
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

        // Getting Custom Dishes Records
        $records_count  = CustomDish::getCustomDishes(null, null, $search, $filter, $sort);
        $records        = CustomDish::getCustomDishes($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $dish_type          = 'N/A';
                $name               = 'N/A';
                $description        = 'N/A';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value['dish_type']->name)){
                    $dish_type = $value['dish_type']->name;
                }                

                if(!empty($value->description)){
                    $description = '<a herf="#" data-url="' . route('nutritionPanel.custom-dishes.viewDescription', ['id' => ev($value->id)]) . '" class="view-description cursor-pointer" title="View Description"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Description</div></a>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="custom_dish_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('nutritionPanel.custom-dishes.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "dish_type"         => $dish_type,
                    "name"              => $name,
                    "description"       => $description,
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
        * View create Custom Dishes.
        *
        * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
        *
        * @author Sandeep
        * @created 20 Jan 2023
    */
    public function create()
    {
        // Get user
        $authUser = auth()->user();
        //----------

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Custom Dishes' => route('nutritionPanel.custom-dishes.index'),
            __('language.create') => '',
        ];

        $dishTypes = DishType::where('status',1)->where('created_by', $authUser['id'])->orderBy('id', 'DESC')->get();

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['dishTypes']  = $dishTypes;

        return view('nutrition-panel.custom-dishes.create')->with($this->viewData);
    }

    /**
     * Store Custom Dishes.
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
 
        $customDish       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Custom Dish
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'dish_type_id'          => $request['dish_type_id'],
                'description'           => $request['description'],
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Custom Dish image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.custom-dishes.image_path'), null, 'custom-dishes-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $customDish = CustomDish::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $customDish       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('CustomDish create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($customDish)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Custom Dish']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.custom-dishes.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Custom Dish']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.custom-dishes.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Custom Dishes.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $customDish = CustomDish::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Custom Dishes' => route('nutritionPanel.custom-dishes.index'),
            __('language.edit') => '',
        ];

        $dishTypes = DishType::where('status',1)->where('created_by', $authUser['id'])->orderBy('id', 'DESC')->get();
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['customDish'] = $customDish;
        $this->viewData['dishTypes']  = $dishTypes;

        return view('nutrition-panel.custom-dishes.edit')->with($this->viewData);
    }

    /**
     * Update Custom Dish.
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
        
        $customDishUpdate  = false;
        $errorMessage       = null;
        
        // Update Custom Dish
        DB::beginTransaction();

        try {

            // Update Custom Dish
            $customDish = CustomDish::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'dish_type_id'          => $request['dish_type_id'],
                'description'           => $request['description'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Custom Dish image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($customDish->image)) {
                    delete_image(config('constants.custom-dishes.image_path'), $customDish->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.custom-dishes.image_path'), null, 'custom-dishes-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $customDishUpdate = CustomDish::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $customDishUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('CustomDish update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($customDishUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Custom Dish']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.custom-dishes.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Custom Dish']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.custom-dishes.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = CustomDish::toggleStatus($request['ids']);
        
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
        $customDish = CustomDish::whereIn('id', $ids)->delete();
        
        // Set response
        if ($customDish == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Custom Dish']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Custom Dish']),
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

            CustomDish::find($value[0])->update($data);
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

    /**
     * View Description.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function viewDescription($id)
    {
        $auth_user = auth()->user();

        // Get Description
        $customDish = CustomDish::where('id', dv($id))->first();

        // Send view data
        $this->viewData['customDish'] = $customDish;

        return view('nutrition-panel.custom-dishes.view-description')->with($this->viewData);
    }

}
