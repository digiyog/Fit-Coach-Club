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
        $authUser = auth()->user();

        $userId = $request->id ? dv($request->id) : null;
        $summary = Order::getOrdersSummary(null, ['user_id' => $userId]);

        $this->viewData['authUser'] = $authUser;
        $this->viewData['user_id'] = $userId;
        $this->viewData['summary'] = $summary;

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
        $order = $request->get('order');
        $sort = (!empty($order) && is_array($order)) ? $order[0] : null;

        $searchVal = $request->get('search');
        $search = is_array($searchVal) ? ($searchVal['value'] ?? null) : null;
        if (empty($search) && !empty($request->search_query)) {
            $search = $request->search_query;
        }

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
        $summary = Order::GetOrdersSummary($search, $filter);

        $arr_data = array();

        if($records_count > 0)
        {
            foreach($records as $key => $value)
            {
                $orderDate      = 'N/A';
                $orderNumber    = 'N/A';
                $user_name      = 'N/A';
                $mobile_number  = 'N/A';
                $total_amount   = 0;
                $discount       = 0;
                $net_amount     = 0;
                $payment_status = '';
                $order_status   = '';
                $action         = '';

                // Preparing Data
                $created = !empty($value->created_at) ? Carbon::parse($value->created_at)->addMinutes(330)->format('d M, Y h:i A') : 'N/A';
                
                $orderDetailUrl = route('nutritionPanel.orders.getOrderDetails', ['id' => ev($value->id)]);
                $transactionInfo = '<div class="order-info-cell">'
                    . '<div class="order-date-text">Date : ' . $created . '</div>'
                    . '<div class="order-num-text">Order Number : <a href="' . $orderDetailUrl . '" class="order-link">#' . e($value->order_number) . '</a></div>'
                    . '</div>';

                // User Info Column
                $rawUserName = !empty($value->user_name) ? $value->user_name : 'N/A';
                $mobile_number = !empty($value->mobile_number) ? e($value->mobile_number) : 'N/A';

                if (!empty($value->user_name)) {
                    $initials = '';
                    $nameWords = explode(' ', trim($rawUserName));
                    foreach ($nameWords as $w) {
                        if (!empty($w)) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 1) ?: 'U';

                    $colors = [
                        ['bg' => '#eff6ff', 'color' => '#2563eb', 'border' => '#bfdbfe'],
                        ['bg' => '#f3e8ff', 'color' => '#9333ea', 'border' => '#e9d5ff'],
                        ['bg' => '#ecfdf5', 'color' => '#059669', 'border' => '#a7f3d0'],
                        ['bg' => '#fffbeb', 'color' => '#d97706', 'border' => '#fde68a'],
                        ['bg' => '#fdf2f8', 'color' => '#db2777', 'border' => '#fbcfe8'],
                    ];
                    $cIdx = abs(crc32($rawUserName)) % count($colors);
                    $avatarCol = $colors[$cIdx];

                    $profileImageUrl = null;
                    if (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    } elseif (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                    }

                    if ($profileImageUrl) {
                        $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 28px; height: 28px; min-width: 28px; object-fit: cover; border: 1.5px solid #e2e8f0;" alt="'.e($rawUserName).'" />';
                    } else {
                        $avatarInner = '<div style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; border: 1.5px solid '.$avatarCol['border'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                    }

                    $user_name_html = '<div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                        '.$avatarInner.'
                        <span class="order-user-name">'.e($rawUserName).'</span>
                    </div>';
                } else {
                    $user_name_html = '<span class="order-user-name">N/A</span>';
                }

                $total_amount = $value->total_amount ?? 0;
                $discount = $value->discount ?? 0;
                $net_amount = $value->net_amount ?? 0;

                // Payment Status interactive pill dropdown
                $statusChangeUrl = route('nutritionPanel.orders.paymentStatusChange');
                $orderIdEnc = ev($value->id);

                if($value->payment_status == 2){
                    $payment_status = '
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn-payment-pill pill-success dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Success <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.$statusChangeUrl.'" data-status="1" data-id="'.$orderIdEnc.'">Pending</a>
                                <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.$statusChangeUrl.'" data-status="2" data-id="'.$orderIdEnc.'">Success</a>
                            </div>
                        </div>';
                } else {
                    $payment_status = '
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn-payment-pill pill-pending dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pending <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.$statusChangeUrl.'" data-status="1" data-id="'.$orderIdEnc.'">Pending</a>
                                <a class="dropdown-item payment-status-change cursor-pointer" data-payment-status-url="'.$statusChangeUrl.'" data-status="2" data-id="'.$orderIdEnc.'">Success</a>
                            </div>
                        </div>';
                }

                // Order Status Badges
                if ($value->order_status == 1) {
                    $order_status = '<span class="badge-order-status status-placed">Order Placed</span>';
                } else if ($value->order_status == 2) {
                    $order_status = '<span class="badge-order-status status-ready">Ready to Ship</span>';
                } else if ($value->order_status == 3) {
                    $order_status = '<span class="badge-order-status status-return">Return</span>';
                } else if ($value->order_status == 4) {
                    $order_status = '<span class="badge-order-status status-shipped">Shipped</span>';
                } else if ($value->order_status == 5) {
                    $order_status = '<span class="badge-order-status status-transit">In Transit</span>';
                } else if ($value->order_status == 6) {
                    $order_status = '<span class="badge-order-status status-delivered">Delivered</span>';
                } else if ($value->order_status == 7) {
                    $order_status = '<span class="badge-order-status status-cancelled">Cancelled</span>';
                } else if ($value->order_status == 8) {
                    $order_status = '<span class="badge-order-status status-refund">Refund</span>';
                } else {
                    $order_status = '<span class="badge-order-status status-placed">Order Placed</span>';
                }

                // Action Dropdown with modern icons
                $changeStatusUrl = route('nutritionPanel.orders.changeStatus');
                $actionItems = '';

                if ($value->order_status == 1) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="2" data-id="'.$orderIdEnc.'"><i class="fa fa-cube action-item-icon"></i> Ready to Ship</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="3" data-id="'.$orderIdEnc.'"><i class="fa fa-undo action-item-icon"></i> Return</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="4" data-id="'.$orderIdEnc.'"><i class="fa fa-truck action-item-icon"></i> Shipped</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="5" data-id="'.$orderIdEnc.'"><i class="fa fa-compass action-item-icon"></i> In Transit</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="6" data-id="'.$orderIdEnc.'"><i class="fa fa-check-circle action-item-icon"></i> Delivered</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="7" data-id="'.$orderIdEnc.'"><i class="fa fa-times-circle action-item-icon"></i> Cancelled</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="8" data-id="'.$orderIdEnc.'"><i class="fa fa-credit-card action-item-icon"></i> Refund</a>';
                } else if ($value->order_status == 2) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="3" data-id="'.$orderIdEnc.'"><i class="fa fa-undo action-item-icon"></i> Return</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="4" data-id="'.$orderIdEnc.'"><i class="fa fa-truck action-item-icon"></i> Shipped</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="5" data-id="'.$orderIdEnc.'"><i class="fa fa-compass action-item-icon"></i> In Transit</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="6" data-id="'.$orderIdEnc.'"><i class="fa fa-check-circle action-item-icon"></i> Delivered</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="7" data-id="'.$orderIdEnc.'"><i class="fa fa-times-circle action-item-icon"></i> Cancelled</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="8" data-id="'.$orderIdEnc.'"><i class="fa fa-credit-card action-item-icon"></i> Refund</a>';
                } else if ($value->order_status == 3) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="8" data-id="'.$orderIdEnc.'"><i class="fa fa-credit-card action-item-icon"></i> Refund</a>';
                } else if ($value->order_status == 4) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="5" data-id="'.$orderIdEnc.'"><i class="fa fa-compass action-item-icon"></i> In Transit</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="6" data-id="'.$orderIdEnc.'"><i class="fa fa-check-circle action-item-icon"></i> Delivered</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="7" data-id="'.$orderIdEnc.'"><i class="fa fa-times-circle action-item-icon"></i> Cancelled</a>';
                } else if ($value->order_status == 5) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="6" data-id="'.$orderIdEnc.'"><i class="fa fa-check-circle action-item-icon"></i> Delivered</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="7" data-id="'.$orderIdEnc.'"><i class="fa fa-times-circle action-item-icon"></i> Cancelled</a>';
                } else if ($value->order_status == 6) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="3" data-id="'.$orderIdEnc.'"><i class="fa fa-undo action-item-icon"></i> Return</a>';
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="8" data-id="'.$orderIdEnc.'"><i class="fa fa-credit-card action-item-icon"></i> Refund</a>';
                } else if ($value->order_status == 7) {
                    $actionItems .= '<a class="dropdown-item change-status-single cursor-pointer" data-change-status-url="'.$changeStatusUrl.'" data-status="8" data-id="'.$orderIdEnc.'"><i class="fa fa-credit-card action-item-icon"></i> Refund</a>';
                }

                $action = '
                    <div class="dropdown custom-action-dropdown">
                        <button type="button" class="btn-action-dots dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end action-dropdown-menu shadow">
                            ' . $actionItems . '
                            ' . (!empty($actionItems) ? '<div class="dropdown-divider"></div>' : '') . '
                            <a class="dropdown-item" href="' . $orderDetailUrl . '"><i class="fa fa-external-link action-item-icon"></i> View Details</a>
                        </div>
                    </div>';

                // Array Data
                $arr_data[] = array(
                    "transaction_info"  => $transactionInfo,
                    "user_name"         => $user_name_html,
                    "mobile_number"     => '<span class="order-mobile-num">' . $mobile_number . '</span>',
                    "total_amount"      => '<span class="order-amount">' . number_format((float)$total_amount, 2, '.', '') . '</span>',
                    "discount"          => '<span class="order-discount">' . (floatval($discount) > 0 ? number_format((float)$discount, 2, '.', '') : '0') . '</span>',
                    "net_amount"        => '<span class="order-net-amount">' . number_format((float)$net_amount, 2, '.', '') . '</span>',
                    "payment_status"    => $payment_status,
                    "order_status"      => $order_status,
                    "action"            => $action
                );
            }
        }
        $totalRecords = $records_count;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data,
            "summary"               => $summary
        );

        return response()->json($response);
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
            $receiverData = ($orderDetail && !empty($orderDetail->user_id)) ? User::where('id', $orderDetail->user_id)->first() : null;

            $senderData['name'] = '';

            if (empty($receiverData) || empty($receiverData['name'])) {
                $receiverDataName = 'Anonymous User';
            } else {
                $receiverDataName = $receiverData['name'];
            }

            if ($orderDetail && $orderDetail->order_status == 1)  {
                $orderStatus = 'Order Placed';
            } else if ($orderDetail && $orderDetail->order_status == 2) {
                $orderStatus = 'Ready to Ship';
            } else if ($orderDetail && $orderDetail->order_status == 3) {
                $orderStatus = 'Return';
            } else if ($orderDetail && $orderDetail->order_status == 4) {
                $orderStatus = 'Shipped';
            } else if ($orderDetail && $orderDetail->order_status == 5) {
                $orderStatus = 'In Transit';
            } else if ($orderDetail && $orderDetail->order_status == 6) {
                $orderStatus = 'Delivered';
            } else if ($orderDetail && $orderDetail->order_status == 7) {
                $orderStatus = 'Cancelled';
            } else if ($orderDetail && $orderDetail->order_status == 8) {
                $orderStatus = 'Refund';
            }
            //------------
        } catch (\Throwable $e) {
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
        $orderDetails = Order::where('id', dv($id))->first();
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
                Transaction::where('order_id', dv($request['ids']))->where('user_id', $orderDetail['user_id'] ?? null)->update([
                    'payment_type' => 'Pending', 
                ]);
            } else {
                Transaction::where('order_id', dv($request['ids']))->where('user_id', $orderDetail['user_id'] ?? null)->update([
                    'payment_type' => 'Received', 
                ]);
            }
            
            //------------
        } catch (\Throwable $e) {
            \Log::error('Order payment status Error: ' . $e->getMessage());
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
