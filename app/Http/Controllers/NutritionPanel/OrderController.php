<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * @var array
     */
    public $viewData = [];

    /**
     * View Order.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * @author Sumit
     * @created 18 Jan 2022
     */
    public function index(Request $request)
    {
        // Get users
        $authUser = auth()->user();
        //----------

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'View Orders' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button

        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'filter',
            'btn_text' => __('language.filter'),
            'attributes' => []
        ];

        // $breadcrumbButton[] = [
        //     'btn_class' => 'btn btn-primary mt-2 rounded-circle create-order',
        //     'btn_link' => 'javascript:;',
        //     'btn_icon' => 'plus',
        //     'btn_text' => __('language.add_button'),
        //     'attributes' => ['data-url' => route('nutritionPanel.orders.addOrder')]
        // ];

        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        if($request->id){
            $this->viewData['user_id'] = dv($request->id);
        } else {
            $this->viewData['user_id'] = $request->id;
        }

        return view('nutrition-panel.orders.index')->with($this->viewData);
    }

    /**
     * Get orders list.
     *
     * @return response
     *
     * @author Sumit
     * @created 18 Jan 2022
     */
    public function getOrders(Request $request)
    {
        $authUser = auth()->user();
        
        // Ajax Post Parameters from Table
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'];

        // Filter data
        $filter = array(
            "filter" => $request->filter,
            "filter_date_range" => $request->filter_date_range,
            'user_id' => $request->user_id,
            "status_filter" => $request->status_filter,
            "payment_status_filter" => $request->payment_status_filter,
        );
        
        // Get Orders list
        $records_count = Order::GetOrders(null, null, $search, $filter, $sort);
        $records = Order::GetOrders($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if($records_count > 0)
        {
            foreach($records as $key => $value)
            {
                $orderDate      = 'N/A';
                $orderNumber    = 'N/A';
                $user_name      = 'N/A';
                $mobileNumber   = 'N/A';
                $total_amount   = 'N/A';
                $discount       = 'N/A';
                $net_amount     = 'N/A';
                $payment_status = '';
                $order_status   = '';
                $action         = '';

    // 13  payment_status  tinyint(4)          No  1   1- Pending 2- Success 3- Failed     Change Change   Drop Drop   
    // 14  order_status    tinyint(4)          No  1   1.Order Placed 2. Ready to ship 4. Shipped 5. In Transit 6. Delivered 7. Cancelled 8. Refund

                // Preparing Data
                $serial = ($key+1);

                // Transaction Info Column
                if(!empty($value->created_at)){
                    $created = Carbon::parse($value->created_at)->addMinutes(330)->format('d M, Y h:i A');
                }
                
                $transactionInfo = 'Date : '.$created;
                
                $orderNumber = '<a target="_new" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">';
                $orderNumber .= '#'.$value->order_number;
                $orderNumber .= '</a>';

                $transactionInfo .= '<br/>Order Number : '.$orderNumber;

                // User Info Column
                $user_name = $value->user_name ?? $user_name;
                $mobile_number = $value->mobile_number ?? $mobileNumber;

                $total_amount = $value->total_amount;
                $discount = $value->discount;
                $net_amount = $value->net_amount;

                if($value->payment_status == 1){
                    $payment_status = 'Pending';
                    $color = 'btn-danger';
                } else if($value->payment_status == 2){
                    $payment_status = 'Success';
                    $color = 'btn-success';
                } else {
                    $payment_status = 'Failed';
                    $color = 'btn-danger';
                }

                $payment_status = '
                    <div class="btn-group">
                        <button type="button" class="btn '.$color.' btn-sm">'.$payment_status.'</button>
                        <button type="button" class="btn '.$color.' btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuReference1">
                          <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.route('nutritionPanel.orders.paymentStatusChange').'" data-status="1" data-id="'.ev($value->id).'">Pending</a>
                          <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.route('nutritionPanel.orders.paymentStatusChange').'" data-status="2" data-id="'.ev($value->id).'">Success</a>
                        </div>
                    </div>
                ';

                if ($value->order_status == 1)  {
                    $order_status = '<label class="badge badge-dark">Order Placed</label>';
                } else if ($value->order_status == 2) {
                    $order_status = '<label class="badge badge-info">Ready to Ship</label>';
                } else if ($value->order_status == 3) {
                    $order_status = '<label class="badge badge-warning">Return</label>';
                } else if ($value->order_status == 4) {
                    $order_status = '<label class="badge badge-dark">Shipped</label>';
                } else if ($value->order_status == 5) {
                    $order_status = '<label class="badge badge-primary">In Transit</label>';
                } else if ($value->order_status == 6) {
                    $order_status = '<label class="badge badge-success">Delivered</label>';
                } else if ($value->order_status == 7) {
                    $order_status = '<label class="badge badge-danger">Cancelled</label>';
                } else if ($value->order_status == 8) {
                    $order_status = '<label class="badge badge-success">Refund</label>';
                }

                if ($value->order_status == 1)  {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="2" data-id="'.ev($value->id).'">Ready to Ship</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="3" data-id="'.ev($value->id).'">Return</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="4" data-id="'.ev($value->id).'">Shipped</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="5" data-id="'.ev($value->id).'">In Transit</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="6" data-id="'.ev($value->id).'">Delivered</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="7" data-id="'.ev($value->id).'">Cancelled</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="8" data-id="'.ev($value->id).'">Refund</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 2) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="3" data-id="'.ev($value->id).'">Return</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="4" data-id="'.ev($value->id).'">Shipped</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="5" data-id="'.ev($value->id).'">In Transit</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="6" data-id="'.ev($value->id).'">Delivered</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="7" data-id="'.ev($value->id).'">Cancelled</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="8" data-id="'.ev($value->id).'">Refund</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 3) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="8" data-id="'.ev($value->id).'">Refund</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 4) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="5" data-id="'.ev($value->id).'">In Transit</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="6" data-id="'.ev($value->id).'">Delivered</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="7" data-id="'.ev($value->id).'">Cancelled</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 5) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="6" data-id="'.ev($value->id).'">Delivered</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="7" data-id="'.ev($value->id).'">Cancelled</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 6) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="3" data-id="'.ev($value->id).'">Return</a>
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="8" data-id="'.ev($value->id).'">Refund</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 7) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.route('nutritionPanel.orders.changeStatus').'" data-status="8" data-id="'.ev($value->id).'">Refund</a>
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                } else if ($value->order_status == 8) {
                    $action = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item" href="'.route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]).'">View Details</a>
                        </div>
                    </div>';
                }

                $action .= '</div> </div>';
                
                // Array Data
                $arr_data[] = array(
                    "transaction_info"  => $transactionInfo,
                    "user_name"         => $user_name,
                    "mobile_number"     => $mobile_number,
                    "total_amount"      => $total_amount,
                    "discount"          => $discount,
                    "net_amount"        => $net_amount,
                    "payment_status"    => $payment_status,
                    "order_status"      => $order_status,
                    "action"            => $action
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
     * Change status.
     *
     * @return boolean
     *
     * @author Rajesh
     * @created 22 Mar 2022
     */
    public function changeStatus(Request $request)
    {
        $booking        = null;
        $errorMessage   = null;
        $message        = null;
        $authUser       = auth()->user();

        try {

            Order::where('id', dv($request['ids']))->update([
                'order_status' => $request['status'], 
            ]);

            $orderDetail = Order::where('id', dv($request['ids']))->first();

            // Send Push Notifications
            $receiverData = User::where('id',$orderDetails->user_id)->first();

            $senderData['name'] = '';

            if($receiverData['name'] == ''){
                $receiverData['name'] = 'Anonymous User';
            } else {
                $receiverData['name'] = $receiverData['name'];
            }

            if ($orderDetail->order_status == 1)  {
                $orderStatus = 'Order Placed';
            } else if ($orderDetail->order_status == 2) {
                $orderStatus = 'Ready to Ship';
            } else if ($orderDetail->order_status == 3) {
                $orderStatus = 'Return';
            } else if ($orderDetail->order_status == 4) {
                $orderStatus = 'Shipped';
            } else if ($orderDetail->order_status == 5) {
                $orderStatus = 'In Transit';
            } else if ($orderDetail->order_status == 6) {
                $orderStatus = 'Delivered';
            } else if ($orderDetail->order_status == 7) {
                $orderStatus = 'Cancelled';
            } else if ($orderDetail->order_status == 8) {
                $orderStatus = 'Refund';
            }
            //------------
        } catch (Exception $e) {
            \Log::error('Order refund Error: ' . $e->getMessage());
        }

        $response = [
            '_status' => true,
            '_message' => __('messages.status_changed'),
            '_type' => 'success',
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Get Order details.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sumit
     * @created 18 Jan 2022
     */
    public function getOrderDetails(Request $request, $id)
    {
        // Get users
        $authUser = auth()->user();
        //----------

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'View Order Details' => '',
        ];

        // Get Order Details
        $orderDetails = Order::where('id', dv($id))
        ->with([
            'orderDetails' => function ($query) use ($defaultLanguage, $search, $filter, $sort) {
            }
        ])
        ->first();
        //------------

        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['orderDetails'] = $orderDetails;

        return view('nutrition-panel.orders.details')->with($this->viewData);
    }

    /**
     * Payment Status Change.
     *
     * @return boolean
     *
     * @author Rajesh
     * @created 22 Mar 2022
     */
    public function paymentStatusChange(Request $request)
    {
        $errorMessage   = null;
        $message        = null;
        $authUser       = auth()->user();

        try {

            Order::where('id', dv($request['ids']))->update([
                'payment_status' => $request['status'], 
            ]);

            $orderDetail = Order::where('id', dv($request['ids']))->first();

            if($request['status'] == 1){
                Transaction::where('order_id', dv($request['ids']))->where('user_id',$orderDetail['user_id'])->update([
                    'payment_type' => 'Pending', 
                ]);
            } else {
                Transaction::where('order_id', dv($request['ids']))->where('user_id',$orderDetail['user_id'])->update([
                    'payment_type' => 'Received', 
                ]);
            }
            
            //------------
        } catch (Exception $e) {
            \Log::error('Order refund Error: ' . $e->getMessage());
        }

        $response = [
            '_status' => true,
            '_message' => __('messages.status_changed'),
            '_type' => 'success',
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Edit Order.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function addOrder()
    {
        $auth_user = auth()->user();

        // Edit Users.
        $users = User::where("users.role_type", 'user')->where("users.created_by", $auth_user->id)->get();

        $this->viewData['products'] = Product::where('status', 1)->get();

        // Send view data
        $this->viewData['users'] = $users;

        return view('nutrition-panel.orders.add-order')->with($this->viewData);
    }

    /**
     * Update Order.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function storeOrder(Request $request)
    {
        // Get Order
        $authUser = auth()->user();
        //----------
        
        $orderUpdate  = false;
        $errorMessage = null;
        
        // Update Order
        DB::beginTransaction();

        try {
            if($request['amount'] - $request['received_amount'] > 0){
                $request['payment_type'] = 'Pending';
            } else {
                $request['payment_type'] = 'Received';
            }

            $transaction = [
                'user_id'           => $request['user'],
                'title'             => 'Admin Manual Add',
                'total_amount'      => $request['amount'],
                'received_amount'   => $request['received_amount'],
                'due_amount'        => $request['amount'] - $request['received_amount'],
                'payment_type'      => $request['payment_type'],
                'remark'            => $request['remark'],
                'created_by'        => $authUser->id
            ];
            
            Transaction::create($transaction);

            User::where('id', $request['user'])->increment('due_amount', $transaction['due_amount']);

            DB::commit();
        } catch (\Exception $e) {
            $transactionUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Order transaction Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        // Set response
        if (!is_null($transactionUpdate)){
            $response = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Transaction']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Transaction']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * Add Transaction.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function editTransaction($id)
    {
        $auth_user = auth()->user();

        // Edit Transaction
        $transaction = Transaction::where('id', dv($id))->first();

        // Send view data
        $this->viewData['transaction'] = $transaction;

        return view('nutrition-panel.transactions.edit-transaction')->with($this->viewData);
    }

    /**
     * Update Transaction.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function updateTransaction(Request $request, $id)
    {
        // Get Transaction
        $authUser = auth()->user();
        //----------
        
        $transactionUpdate  = false;
        $errorMessage       = null;
        
        // Update Transaction
        DB::beginTransaction();

        try {
            $transaction = Transaction::where('id', dv($id))->first();
            $userId = $transaction->user_id;
            
            User::where('id', $transaction->user_id)->decrement('due_amount', $transaction['due_amount']);

            if($request['amount'] - $request['received_amount'] > 0){
                $request['payment_type'] = 'Pending';
            } else {
                $request['payment_type'] = 'Received';
            }

            $transaction = [
                'total_amount'      => $request['amount'],
                'received_amount'   => $request['received_amount'],
                'due_amount'        => $request['amount'] - $request['received_amount'],
                'payment_type'      => $request['payment_type'],
                'remark'            => $request['remark'],
            ];
            
            Transaction::where('id', dv($id))->update($transaction);

            User::where('id', $userId)->increment('due_amount', $transaction['due_amount']);

            DB::commit();
        } catch (\Exception $e) {
            $transactionUpdate = null;
            \Log::error('Order transaction2 Error: ' . $e->getMessage());
            DB::rollback();
        }
        // ------------

        // Set response
        if (!is_null($transactionUpdate)){
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Transaction']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Transaction']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    public function viewRemark($id)
    {
        $auth_user = auth()->user();
        $remark      = Transaction::where('id', dv($id))->first();

        // Send view data
        $this->viewData['remark'] = $remark;

        return view('nutrition-panel.transactions.view-remark')->with($this->viewData);
    }
}
