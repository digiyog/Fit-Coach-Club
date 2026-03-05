<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;

use App\Models\Product;
use App\Models\ProductImage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    use UploadImage;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * View Product.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        $products = Product::select('id', 'name', 'price', 'image', 'short_description')
        // ->with(['franchise' => function ($query) use ($request, $userInfo) {
        //     $query->where('franchise_id', $userInfo->created_by);
        // }])
        ->whereHas('franchise', function ($query) use ($request, $userInfo) {
            $query->where('franchise_id', $userInfo->created_by);
        })
        ->where('status',1)
        ->where('product_type_id', $userInfo->product_type_id)
        ->orderBy('order', 'ASC')
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($products)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'product']),
                '_data'    => $products
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'product']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * View Product Details.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function details(Request $request)
    {
        $userInfo = Auth::user();

        $product = Product::with('product_images')
        ->where('status',1)
        ->where('id',$request['product_id'])
        ->first(); 

        // Set response
        if (!empty($product)) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'product details']),
                '_data'    => $product
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'product details']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

}
