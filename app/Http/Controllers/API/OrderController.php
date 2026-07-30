<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Transaction;

use Illuminate\Support\Facades\Auth;
use DB;
use Mail;

class OrderController extends Controller
{
    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Place Order.
     * 
     * @param  Place Order  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function placeOrder(Request $request){
        // Get user
        $user = Auth::user();
        //---------

        try {
            $orderDetails = json_decode($request['order_details'],true);

            foreach($orderDetails as $key => $orders) {
                $productDetails = Product::where('id',$orders['id'])->first();

                $total_quantity += $orders['qty'];
                $total_price    += $productDetails['price']*$orders['qty'];
            }

            // if ($user['discount'] > 0) {
            //     $discount = ($total_price * $user['discount']) / 100;
            // } else {
                $discount = 0;
            // }


            $orderPlaced = Order::create([
                'franchise_id'              => $user->created_by,
                'user_id'                   => $user->id,
                'order_id'                  => time(),
                'order_date'                => date('Y-m-d'),
                'product_quantity'          => $total_quantity,
                'total_amount'              => $total_price,
                'discount'                  => $discount,
                'net_amount'                => $total_price - $discount,
                'user_name'                 => $user['name'],
                'mobile_number'             => $user['mobile_number'],
                'payment_mode'              => 0,
                'payment_status'            => 1,
                'order_status'              => 1,
            ]);

            if (!is_null($orderPlaced)) {
                $order                    = Order::find($orderPlaced->id);
                $order->order_number      = "fitcoachclub_00".$order->id;
                $order->save();
            }

            if (!is_null($orderPlaced)) {
                foreach($orderDetails as $key => $orders) {
                    $productDetails = Product::where('id',$orders['id'])->first();

                    $orderDetail = OrderDetail::create([
                        'order_id'              => $orderPlaced->id,
                        'product_id'            => $orders['id'],
                        'name'                  => $productDetails['name'],
                        'quantity'              => $orders['qty'],
                        'image'                 => $productDetails['image'],
                        'price'                 => $productDetails['price'],
                        'net_amount'            => $productDetails['price']*$orders['qty']
                    ]);
                }
                //---------------------
            }

            Transaction::create([
                'user_id'           => $user->id,
                'order_id'          => $orderPlaced->id,
                'title'             => 'Order Placed',
                'total_amount'      => $total_price,
                'received_amount'   => 0,
                'due_amount'        => $total_price,
                'type'              => 1,
                'payment_type'      => 'Pending',
                'created_by'        => $user->created_by,
            ]);

            User::where('id', $user->id)->increment('due_amount', $total_price);

        } catch (\Exception $e) {
            \Log::error('Order place Error: ' . $e->getMessage());
            $orderPlaced = null;
        }

        // Set response
        if (!is_null($orderPlaced)) {
            $response = [
                '_status'   => true,
                '_message'  => __('messages.place_order_success'),
                '_data' => [
                    'order_id' => $orderPlaced->order_id,
                ],
            ];
        } else {
            $response = [
                '_status'   => false,
                '_message'  => __('messages.place_order_failed'),
                '_data'     => null,
            ];
        }
        //-------------
        return response()->json($response, 200);
    }

    /**
     * My Orders.
     * 
     * @param My Orders $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        // Get user
        $user = Auth::user();
        //---------

        $orderInfo = Order::where('user_id', $user->id)->select('id', 'user_id', 'order_number', 'order_date', 'product_quantity', 'total_amount', 'discount', 'net_amount')
        ->whereHas('orderDetails', function ($query) use ($request) {
        })
        ->orderBy('id','DESC')->get();

        // Set response
        if (!empty($orderInfo->toArray())) {
            $response = [
                '_status' => true,
                '_message' => __('messages.order_found'),
                '_data' => $orderInfo,
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.order_not_found'),
                '_data' => null,
            ];
        }
        //-------------
        return response()->json($response, 200);
    }

    /**
     * Order Details.
     * 
     * @param Order Details $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function details(Request $request)
    {
        // Get user
        $user = Auth::user();
        //---------

        $orderInfo = Order::where('user_id', $user->id)->select('id', 'user_id', 'order_number', 'order_date', 'product_quantity', 'total_amount', 'discount', 'net_amount')
        ->with(['orderDetails' => function ($query) use ($request , $userInfo) {
            $query->select('id', 'order_id', 'product_id', 'name', 'quantity', 'image', 'price', 'net_amount');
        }])
        ->whereHas('orderDetails', function ($query) use ($request) {
        })
        ->where('id', $request['id'])
        ->first();

        // Set response
        if (!is_null($orderInfo)) {

            $response = [
                '_status' => true,
                '_message' => __('messages.order_found'),
                '_data' => $orderInfo
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.order_not_found'),
                '_data' => null,
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
