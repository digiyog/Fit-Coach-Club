<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Transaction;
use App\Models\User;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class TransactionController extends Controller
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
     * View Transactions list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index()
    {
        $authUser = auth()->user();

        // Calculate initial summary stats
        $summary = Transaction::getTransactionsSummary();

        // View Data
        $this->viewData['authUser'] = $authUser;
        $this->viewData['totalAmount'] = $summary['total_amount'];
        $this->viewData['receivedAmount'] = $summary['received_amount'];
        $this->viewData['dueAmount'] = $summary['due_amount'];
        $this->viewData['collectionRate'] = $summary['collection_rate'];
        $this->viewData['totalAmountFormatted'] = $summary['total_amount_formatted'];
        $this->viewData['receivedAmountFormatted'] = $summary['received_amount_formatted'];
        $this->viewData['dueAmountFormatted'] = $summary['due_amount_formatted'];
        $this->viewData['collectionRateFormatted'] = $summary['collection_rate_formatted'];
        
        return view('nutrition-panel.transactions.index')->with($this->viewData);
    }

    /**
     * Get Transactions list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getTransactions(Request $request)
    {
        $authUser = auth()->user();   

        // Ajax Post Parameters
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $order  = $request->get('order');
        $sort   = (!empty($order) && is_array($order)) ? $order[0] : null;
        
        $searchVal = $request->get('search');
        $search = is_array($searchVal) ? ($searchVal['value'] ?? null) : null;
        if (empty($search) && !empty($request->search_query)) {
            $search = $request->search_query;
        }
        
        // Filter Parameters
        $filter = array(
            "name"              => $request->name,
            "date_range"        => $request->date_range,
            "payment_type"      => $request->payment_type,
            "collection_state"  => $request->collection_state,
        );

        // Getting Transactions Records
        $records_count  = Transaction::getTransactions(null, null, $search, $filter, $sort);
        $records        = Transaction::getTransactions($limit, $start, $search, $filter, $sort);
        $summary        = Transaction::getTransactionsSummary($search, $filter);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $user_name          = 'N/A';
                $order_number       = 'N/A';
                $title              = 'N/A';
                $total_amount       = 0;
                $due_amount         = 0;
                $received_amount    = 0;
                $payment_type       = 'N/A';
                $remark             = 'N/A';
                $date               = 'N/A';
                $action             = '';

                // Preparing Data
                $user_name_html = '<span class="user-name-text">N/A</span>';
                if(!empty($value->name)){
                    $rawName = $value->name;
                    $email = !empty($value->email) ? $value->email : '';

                    $initials = '';
                    $nameWords = explode(' ', trim($rawName));
                    foreach ($nameWords as $w) {
                        if (!empty($w)) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 2);
                    if (empty($initials)) $initials = 'U';

                    $colors = [
                        ['bg' => '#eff6ff', 'color' => '#2563eb', 'border' => '#bfdbfe'],
                        ['bg' => '#f3e8ff', 'color' => '#9333ea', 'border' => '#e9d5ff'],
                        ['bg' => '#ecfdf5', 'color' => '#059669', 'border' => '#a7f3d0'],
                        ['bg' => '#fffbeb', 'color' => '#d97706', 'border' => '#fde68a'],
                        ['bg' => '#fdf2f8', 'color' => '#db2777', 'border' => '#fbcfe8'],
                    ];
                    $cIdx = abs(crc32($rawName)) % count($colors);
                    $avatarCol = $colors[$cIdx];

                    $profileImageUrl = null;
                    if (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    } elseif (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                    }

                    if ($profileImageUrl) {
                        $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 32px; height: 32px; min-width: 32px; object-fit: cover; border: 1.5px solid #e2e8f0;" alt="'.e($rawName).'" />';
                    } else {
                        $avatarInner = '<div style="width: 32px; height: 32px; min-width: 32px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; border: 1.5px solid '.$avatarCol['border'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11.5px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                    }

                    $emailHtml = !empty($email) ? '<div class="user-email-text" style="font-size: 11px; color: #64748b; line-height: 1.2; margin-top: 1px;">'.e($email).'</div>' : '';

                    $user_name_html = '<div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                        '.$avatarInner.'
                        <div style="line-height: 1.2;">
                            <span class="user-name-text" style="font-weight: 600; color: #0f172a; font-size: 13px;">'.e($rawName).'</span>
                            '.$emailHtml.'
                        </div>
                    </div>';
                }

                if(!empty($value->order_info->order_number)){
                    $order_number = e($value->order_info->order_number);
                }

                if(!empty($value->title)){
                    $title = e($value->title);
                }

                if(!empty($value->total_amount)){
                    $total_amount = $value->total_amount;
                }

                if(!empty($value->due_amount)){
                    $due_amount = $value->due_amount;
                }

                if(!empty($value->received_amount)){
                    $received_amount = $value->received_amount;
                }

                // Payment type formatting
                if($value->title == 'Order Placed' || $value->type == 1){
                    $payment_type_badge = '<span class="badge-type-product">Product</span>';
                } else {
                    $payment_type_badge = '<span class="badge-type-subscription">Subscription</span>';
                }

                // Total Amount formatted
                $total_amount_html = '<span class="amount-total">' . number_format((float)$total_amount, 2, '.', '') . '</span>';

                // Due Amount formatted
                if(floatval($due_amount) > 0){
                    $due_display = (floatval($due_amount) == intval($due_amount)) ? intval($due_amount) : number_format($due_amount, 2, '.', '');
                    $due_amount_html = '<span class="amount-due-active">' . $due_display . '</span>';
                } else {
                    $due_amount_html = '<span class="amount-zero">0</span>';
                }

                // Received Amount formatted
                if(floatval($received_amount) > 0){
                    $received_display = (floatval($received_amount) == intval($received_amount)) ? intval($received_amount) : number_format($received_amount, 2, '.', '');
                    $received_amount_html = '<span class="amount-received-active">' . $received_display . '</span>';
                } else {
                    $received_amount_html = '<span class="amount-zero">0</span>';
                }

                // Remark button / text
                if(!empty($value->remark)){
                    $remark = '<a href="javascript:;" data-url="' . route('nutritionPanel.transactions.viewRemark', ['id' => ev($value->id)]) . '" class="view-remark btn-remark-pill" title="View Remark"><i class="fa fa-eye"></i> View remark</a>';
                } else {
                    $remark = '<span class="text-muted">N/A</span>';
                }

                if(!empty($value->created_at)){
                    $date = date('d-m-Y', strtotime($value->created_at));
                }

                // Edit action button
                $action = '<a href="javascript:;" class="update-transaction btn-edit-action" data-url="' . route('nutritionPanel.transactions.editTransaction', ['id' => ev($value->id)]) . '" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';

                // Array Data
                $arr_data[] = array(
                    "user_name"         => $user_name_html,
                    "order_number"      => '<span class="order-number-text">' . $order_number . '</span>',
                    "title"             => '<span class="title-text">' . $title . '</span>',
                    "total_amount"      => $total_amount_html,
                    "due_amount"        => $due_amount_html,
                    "received_amount"   => $received_amount_html,
                    "payment_type"      => $payment_type_badge,
                    "remark"            => $remark,
                    "date"              => '<span class="date-text">' . $date . '</span>',
                    "action"            => $action,
                );
            }
        }

        $totalRecords = $records_count;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data,
            "summary"               => $summary,
        );

        return response()->json($response);
    }

    /**
     * Add Transaction.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function addTransaction()
    {
        $auth_user = auth()->user();

        // Users
        $users = User::where("users.role_type", 'user')->where("users.created_by", $auth_user->id)->get();

        // Send view data
        $this->viewData['users'] = $users;

        return view('nutrition-panel.transactions.add-transaction')->with($this->viewData);
    }

    /**
     * Store Transaction.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function storeTransaction(Request $request)
    {
        $authUser = auth()->user();
        
        $validator = \Validator::make($request->all(), [
            'user'            => 'required',
            'amount'          => 'required|numeric|min:0',
            'received_amount' => 'required|numeric|min:0',
            'type'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                '_status'  => false,
                '_message' => $validator->errors()->first(),
                '_type'    => 'error',
            ], 200);
        }

        $transactionCreated = false;
        $errorMessage       = null;
        
        DB::beginTransaction();

        try {
            $amount = (float)($request->input('amount', 0));
            $receivedAmount = (float)($request->input('received_amount', 0));
            $dueAmount = max(0, $amount - $receivedAmount);
            $paymentType = ($dueAmount > 0) ? 'Pending' : 'Received';
            $userId = $request->input('user');

            $transaction = [
                'user_id'           => $userId,
                'title'             => 'Admin Manual Add',
                'total_amount'      => $amount,
                'received_amount'   => $receivedAmount,
                'type'              => $request->input('type', 0),
                'due_amount'        => $dueAmount,
                'payment_type'      => $paymentType,
                'remark'            => $request->input('remark'),
                'created_by'        => $authUser->id,
            ];
            
            Transaction::create($transaction);

            if (!empty($userId)) {
                $user = User::find($userId);
                if ($user) {
                    $user->due_amount = max(0, (float)($user->due_amount ?? 0) + $dueAmount);
                    $user->save();
                }
            }

            DB::commit();
            $transactionCreated = true;
        } catch (\Exception $e) {
            $transactionCreated = false;
            $errorMessage = $e->getMessage();
            \Log::error('Transaction store Error: ' . $e->getMessage());
            DB::rollback();
        }

        if ($transactionCreated) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_created', ['record' => 'Transaction']),
                '_type'    => 'success',
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => $errorMessage ?: __('messages.record_creation_failed', ['record' => 'Transaction']),
                '_type'    => 'error',
            ];
        }
        
        return response()->json($response, 200);
    }

    /**
     * Edit Transaction.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function editTransaction($id)
    {
        $auth_user = auth()->user();

        $transactionId = is_numeric($id) ? (int)$id : dv($id);
        $transaction = Transaction::with('user')->where('id', $transactionId)->first();

        if (!$transaction) {
            return '<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body p-4 text-center text-danger"><p>Transaction not found.</p></div>';
        }

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
        $authUser = auth()->user();
        
        $validator = \Validator::make($request->all(), [
            'amount'          => 'required|numeric|min:0',
            'received_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                '_status'  => false,
                '_message' => $validator->errors()->first(),
                '_type'    => 'error',
            ], 200);
        }

        $transactionUpdated = false;
        $errorMessage       = null;
        
        DB::beginTransaction();

        try {
            $transactionId = is_numeric($id) ? (int)$id : dv($id);
            $transactionRecord = Transaction::where('id', $transactionId)->first();

            if (!$transactionRecord) {
                throw new \Exception(__('messages.record_not_found', ['record' => 'Transaction']));
            }

            $amount = (float)($request->input('amount', 0));
            $receivedAmount = (float)($request->input('received_amount', 0));
            $newDue = max(0, $amount - $receivedAmount);
            $oldDue = (float)($transactionRecord->due_amount ?? 0);
            $dueDifference = $newDue - $oldDue;

            $paymentType = ($newDue > 0) ? 'Pending' : 'Received';

            $updateData = [
                'total_amount'      => $amount,
                'received_amount'   => $receivedAmount,
                'due_amount'        => $newDue,
                'payment_type'      => $paymentType,
                'remark'            => $request->input('remark'),
            ];

            if ($request->has('type')) {
                $updateData['type'] = $request->input('type');
            }
            
            $transactionRecord->update($updateData);

            if (!empty($transactionRecord->user_id)) {
                $user = User::find($transactionRecord->user_id);
                if ($user) {
                    $user->due_amount = max(0, (float)($user->due_amount ?? 0) + $dueDifference);
                    $user->save();
                }
            }

            DB::commit();
            $transactionUpdated = true;
        } catch (\Exception $e) {
            $transactionUpdated = false;
            $errorMessage = $e->getMessage();
            \Log::error('Transaction update Error: ' . $e->getMessage());
            DB::rollback();
        }

        if ($transactionUpdated) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.records_updated', ['record' => 'Transaction']),
                '_type'    => 'success',
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => $errorMessage ?: __('messages.records_updation_failed', ['record' => 'Transaction']),
                '_type'    => 'error',
            ];
        }
        
        return response()->json($response, 200);
    }

    public function viewRemark($id)
    {
        $auth_user = auth()->user();
        $remarkId = is_numeric($id) ? (int)$id : dv($id);
        $remark = Transaction::where('id', $remarkId)->first();

        // Send view data
        $this->viewData['remark'] = $remark;

        return view('nutrition-panel.transactions.view-remark')->with($this->viewData);
    }
}