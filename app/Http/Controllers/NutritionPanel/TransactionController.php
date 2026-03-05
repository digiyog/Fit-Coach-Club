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

                if(!empty($value->payment_type)){
                    $payment_type = $value->payment_type;
                }

                if(!empty($value->remark)){
                    $remark = '<a herf="#" data-url="' . route('nutritionPanel.transactions.viewRemark', ['id' => ev($value->id)]) . '" class="view-remark cursor-pointer" title="View Remark"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Remark</div></a>';
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
     * Edit Transaction.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function addTransaction()
    {
        $auth_user = auth()->user();

        // Edit Users.
        $users = User::where("users.role_type", 'user')->where("users.created_by", $auth_user->id)->get();

        // Send view data
        $this->viewData['users'] = $users;

        return view('nutrition-panel.transactions.add-transaction')->with($this->viewData);
    }

    /**
     * Update Transaction.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function storeTransaction(Request $request)
    {
        // Get Transaction
        $authUser = auth()->user();
        //----------
        
        $transactionUpdate  = false;
        $errorMessage       = null;
        
        // Update Transaction
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