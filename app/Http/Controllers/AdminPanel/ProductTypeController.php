<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\ProductType;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ProductTypeController extends Controller
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
     * View Product Types list.
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
            'Product Types' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.product-types.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('admin-panel.product-types.index')->with($this->viewData);
    }

    /**
     * Get Product Types list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getProductTypes(Request $request)
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

        // Getting Product Types Records
        $records_count  = ProductType::getProductTypes(null, null, $search, $filter, $sort);
        $records        = ProductType::getProductTypes($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.product-types.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
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
        * View create Product Types.
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
            'Product Types' => route('adminPanel.product-types.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('admin-panel.product-types.create')->with($this->viewData);
    }

    /**
     * Store Product Types.
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
 
        $productType       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Product Type
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];
            
            $productType = ProductType::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $productType       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('ProductType create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($productType)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Product Types.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $productType = ProductType::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Product Types' => route('adminPanel.product-types.index'),
            __('language.edit') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['productType'] = $productType;

        return view('admin-panel.product-types.edit')->with($this->viewData);
    }

    /**
     * Update Product Type.
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
        
        $productTypeUpdate  = false;
        $errorMessage       = null;
        
        // Update Product Type
        DB::beginTransaction();

        try {

            // Update Product Type
            $productType = ProductType::where('id', dv($id))->first();

            $data = [
                'name'       => $request['name'],
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
            
            $productTypeUpdate = ProductType::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $productTypeUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('ProductType update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($productTypeUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.edit')->withInput()->with(['notification' => $notification]);
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
        $language = ProductType::toggleStatus($request['ids']);
        
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
        $productType = ProductType::whereIn('id', $ids)->delete();
        
        // Set response
        if ($productType == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

}
