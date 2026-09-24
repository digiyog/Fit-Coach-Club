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

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Transactions' => '',
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

        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle create-transaction',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => ['data-url' => route('nutritionPanel.transactions.addTransaction')]
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
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
        $sort   = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "name" => $request->name,
            "date_range" => $request->date_range,
        );

        // Getting Transactions Records
        $records_count  = Transaction::getTransactions(null, null, $search, $filter, $sort);
        $records        = Transaction::getTransactions($limit, $start, $search, $filter, $sort);

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
                if(!empty($value->name)){
                    $user_name = $value->name;
                }

                if(!empty($value->order_info->order_number)){
                    $order_number = $value->order_info->order_number;
                }

                if(!empty($value->title)){
                    $title = $value->title;
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

                // if(!empty($value->payment_type)){
                //     $payment_type = $value->payment_type;
                // }

                if($value->title == 'Order Placed' || $value->type == 1){
                    $payment_type = 'Product';
                } else {
                    $payment_type = 'Subscription';
                }

                if(!empty($value->remark)){
                    $remark = '<a href="javascript:;" data-url="' . route('nutritionPanel.transactions.viewRemark', ['id' => ev($value->id)]) . '" class="view-remark cursor-pointer" title="View Remark"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Remark</div></a>';
                }

                if(!empty($value->created_at)){
                    $date = date('d-m-Y', strtotime($value->created_at));
                }

                if ($value->payment_type == 'Pending')  {
                    $order_status = '<label class="badge badge-danger">Pending</label>';
                } else {
                    $order_status = '<label class="badge badge-success">Received</label>';
                }

                $action = '<a class="update-transaction cursor-pointer" data-url="' . route('nutritionPanel.transactions.editTransaction', ['id' => ev($value->id)]) . '"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "user_name"         => $user_name,
                    "order_number"      => $order_number,
                    "title"             => $title,
                    "total_amount"      => $total_amount,
                    "due_amount"        => $due_amount,
                    "received_amount"   => $received_amount,
                    "payment_type"      => $payment_type,
                    "remark"            => $remark,
                    "date"              => $date,
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