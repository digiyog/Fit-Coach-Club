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

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['id'] = $id;
        
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
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="dish_type_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('nutritionPanel.dish-types.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

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
