<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\FranchiseProduct;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ProductController extends Controller
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
     * View Products list.
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
            'Products' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.products.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('admin-panel.products.index')->with($this->viewData);
    }

    /**
     * Get Products list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getProducts(Request $request)
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

        // Getting Products Records
        $records_count  = Product::getProducts(null, null, $search, $filter, $sort);
        $records        = Product::getProducts($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $product_type       = 'N/A';
                $name               = 'N/A';
                $price              = 'N/A';
                $short_description  = 'N/A';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->product_type->name)){
                    $product_type = $value->product_type->name;
                }

                if(!empty($value->price)){
                    $price = $value->price;
                }

                if(!empty($value->short_description)){
                    $short_description = $value->short_description;
                }

                if(!empty($value->description)){
                    $description = '<a herf="#" data-url="' . route('adminPanel.products.viewDescription', ['id' => ev($value->id)]) . '" class="view-description cursor-pointer" title="View Description"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Description</div></a>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="product_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.products.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    'product_type'      => $product_type,
                    "name"              => $name,
                    "price"             => $price,
                    "short_description" => $short_description,
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
        * View create Products.
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
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Products' => route('adminPanel.products.index'),
            __('language.create') => '',
        ];

        $franchises = User::select('id', 'name')->where("users.role_type", 'franchise')->orderBy('id', 'DESC')->get();

        $productTypes = ProductType::where('status', 1)->get();

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['franchises'] = $franchises;
        $this->viewData['productTypes'] = $productTypes;

        return view('admin-panel.products.create')->with($this->viewData);
    }

    /**
     * Store Products.
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
 
        $product        = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Product
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'price'                 => $request['price'],
                'product_type_id'       => $request['product_type'],
                'short_description'     => $request['short_description'],
                'description'           => $request['description'],
                'order'                 => $request['order'],
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Product image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.products.image_path'), null, 'products-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $product = Product::create($data);

            // Multiple Images
            if($request['multiple_images']) {
                foreach($request['multiple_images'] as $key => $imageGroup) {
                    foreach($imageGroup as $key => $image) {

                        $imageData = [
                            'product_id'     => $product->id,
                        ];

                        // Upload the image
                        $uploadedImage = $this->uploadImage($image, config('constants.products.image_path'), null, 'product-image');
                        if ($uploadedImage['_status']) {
                            $imageData['image'] = $uploadedImage['_data'];
                        }

                        ProductImage::create($imageData);
                    }
                }
            }

            // Add Franchise Product Data
            if(isset($request['franchises']) && count($request['franchises']) > 0)
            {
                foreach($request['franchises'] as $key => $value)
                {
                    $franchiseData = [
                        'franchise_id'      => $value,
                        'product_id'        => $product->id,
                    ];

                    FranchiseProduct::create($franchiseData);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            $product       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('Product create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($product)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Product']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.products.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Product']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.products.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Products.
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
        
        $product = Product::where('id', dv($id))->first();
        $productImage = ProductImage::where('product_id',$product->id)->get();
        $franchiseProduct = FranchiseProduct::where('product_id',$product->id)->get();
        $franchises = User::select('id', 'name')->where("users.role_type", 'franchise')->orderBy('id', 'DESC')->get();
        $productTypes = ProductType::where('status', 1)->get();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Products' => route('adminPanel.products.index'),
            __('language.edit') => '',
        ];

        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['product'] = $product;
        $this->viewData['productImage'] = $productImage;
        $this->viewData['franchiseProduct'] = $franchiseProduct;
        $this->viewData['franchises'] = $franchises;
        $this->viewData['productTypes'] = $productTypes;

        return view('admin-panel.products.edit')->with($this->viewData);
    }

    /**
     * Update Product.
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
        
        $productUpdate  = false;
        $errorMessage   = null;
        
        // Update Product
        DB::beginTransaction();

        try {

            // Update Product
            $product = Product::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'price'                 => $request['price'],
                'product_type_id'       => $request['product_type'],
                'short_description'     => $request['short_description'],
                'description'           => $request['description'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Product image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($product->image)) {
                    delete_image(config('constants.products.image_path'), $product->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.products.image_path'), null, 'products-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $productUpdate = Product::where('id', dv($id))->update($data);

            // Multiple Images
            if($request['multiple_images']) {
                foreach($request['multiple_images'] as $key => $imagesGroup) {
                    foreach ($imagesGroup as $image) {
                        $imageData = [
                            'product_id'     => $product->id,
                            'updated_at'     => Carbon::now()->toDateTimeString(),
                        ];

                        // Upload the image
                        $uploadedImage = $this->uploadImage($image, config('constants.products.image_path'), null, 'product-image');
                        if ($uploadedImage['_status']) {
                            $imageData['image'] = $uploadedImage['_data'];
                        }

                        // Save to the database
                        ProductImage::create($imageData);
                    }
                }
            }

            // Add Franchise Product Data
            if(isset($request['franchises']) && count($request['franchises']) > 0)
            {
                FranchiseProduct::where('product_id', dv($id))->forceDelete();

                foreach($request['franchises'] as $key => $value){
                    $franchiseData = [
                        'franchise_id'      => $value,
                        'product_id'        => dv($id),
                    ];

                    FranchiseProduct::create($franchiseData);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            $productUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Product update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($productUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Product']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.products.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Product']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.products.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = Product::toggleStatus($request['ids']);
        
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
        $product = Product::whereIn('id', $ids)->delete();
        
        // Set response
        if ($product == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Product']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Product']),
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

            Product::find($value[0])->update($data);
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
        $product = Product::where('id', dv($id))->first();

        // Send view data
        $this->viewData['product'] = $product;

        return view('admin-panel.products.view-description')->with($this->viewData);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::find($id);

        if ($image) {
            $path = public_path('uploads/products/images/' . $image->image);
            if (file_exists($path)) {
                @unlink($path);
            }

            $image->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Image not found']);
    }

}
