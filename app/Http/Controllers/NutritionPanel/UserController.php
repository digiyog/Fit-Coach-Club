<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Models\MealType;
use App\Models\ProductType;
use App\Models\AttendanceLogs;
use App\Models\Attendance;
use App\Models\Transaction;
use App\Http\Traits\UploadImage;
use Storage;
use App\Models\Notification;
use Cviebrock\EloquentSluggable\Services\SlugService;

class UserController extends Controller
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
     * View Users list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index($userType=false)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Users' => '',
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
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.users.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['userType'] = $userType;
        
        return view('nutrition-panel.users.index')->with($this->viewData);
    }

    /**
     * Get Users list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getUsers(Request $request)
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
            "email" => $request->email,
            "mobile_number" => $request->mobile_number,
            "date_range" => $request->date_range,
            'user_type' => $request['user_type']
        );

        // Getting Users Records
        $records_count  = User::getUsers(null, null, $search, $filter, $sort);
        $records        = User::getUsers($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $user_type      = 'N/A';
                $name           = 'N/A';
                $email          = 'N/A';
                $mobile_number  = 'N/A';
                $coach_name     = 'N/A';
                $meal_type      = 'N/A';
                $product_type   = 'N/A';
                $due_amount     = 'N/A';
                $status         = '';
                $action         = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if($value->user_type == 'Regular User'){
                    $user_type = $value->user_type.' ('.$value->user_state.')';
                } else {
                    $user_type = $value->user_type;
                }

                if(!empty($value->email)){
                    $email = $value->email;
                }

                if(!empty($value->mobile_number)){
                    $mobile_number = $value->mobile_number;
                }

                if(!empty($value->coach_name)){
                    $coach_name = $value->coach_name;
                }

                if(!empty($value->meal_type->name)){
                    $meal_type = $value->meal_type->name;
                }

                if(!empty($value->product_type->name)){
                    $product_type = $value->product_type->name;
                }

                $days = $value->days;

                if (!empty($value->due_amount)) {
                    $due_amount = $value->due_amount; // abs() se positive value milti hai
                } else {
                    $due_amount = 0;
                }


                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                // $action = '<a href="' . route('nutritionPanel.users.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                $action = '<div class="dropdown custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.edit', ['id' => ev($value->id)]).'">Edit</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.viewWeights', ['id' => ev($value->id)]).'">View Weight</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.viewAttendance', ['id' => ev($value->id)]).'">View Attendance</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.manual-attendances.manual-attendance', ['id' => ev($value->id)]).'">Manual Attendance</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.track-shake.index', ['id' => ev($value->id)]).'">Track Shake</a>
                        <a class="dropdown-item edit-user-quick cursor-pointer" data-url="' . route('nutritionPanel.users.editUserQuick', ['id' => ev($value->id)]) . '">Edit User Quick</a>
                        <a class="dropdown-item add-user-days cursor-pointer" data-url="' . route('nutritionPanel.users.addUserDays', ['id' => ev($value->id)]) . '">Add User Days</a>
                        <a class="dropdown-item subtract-user-days cursor-pointer" data-url="' . route('nutritionPanel.users.subtractUserDays', ['id' => ev($value->id)]) . '">Subtract User Days</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.orders.index', ['id' => ev($value->id)]).'">Purchase Products</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.details', ['id' => ev($value->id)]).'">View Details</a>
                    </div>
                </div>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "user_type"         => $user_type,
                    "name"              => $name,
                    "email"             => $email,
                    "mobile_number"     => $mobile_number,
                    "coach_name"        => $coach_name,
                    "meal_type"         => $meal_type,
                    "product_type"      => $product_type,
                    "days"              => $days,
                    "due_amount"        => $due_amount,
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
        * View create Users.
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
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Users' => route('nutritionPanel.users.index'),
            __('language.create') => '',
        ];

        $mealTypes = MealType::where('status',1)->orderBy('id', 'DESC')->get();
        $productTypes = ProductType::where('status',1)->orderBy('id', 'DESC')->get();

        // View Data
        $this->viewData['breadcrumb']       = $breadcrumb;
        $this->viewData['mealTypes']        = $mealTypes;
        $this->viewData['productTypes']     = $productTypes;

        return view('nutrition-panel.users.create')->with($this->viewData);
    }

    /**
     * Store Users.
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
 
        $user           = null;
        $errorMessage   = null;
        
        // Begin Transaction
        DB::beginTransaction();

        if($request['user_type'] == 'Demo User'){
            $request['days'] = 1;
        } else if($request['user_type'] == '3 Days Trial'){
            $request['days'] = 3;
        } else {
            $request['days'] = 0;
        }
        
        // Create User
        try {

            // Set data
            $data = [
                'name'                      => $request['name'],
                'email'                     => $request['email'],
                'email_verified_at'         => Carbon::now()->toDateTimeString(),
                'mobile_number'             => $request['mobile_number'],
                'mobile_number_verified_at' => Carbon::now()->toDateTimeString(),
                'date_of_birth'             => date('Y-m-d',strtotime($request['date_of_birth'])),
                'user_type'                 => $request['user_type'],
                'user_state'                => $request['user_state'],
                'coach_name'                => $request['coach_name'],
                'meal_type_id'              => $request['meal_type_id'],
                'product_type_id'           => $request['product_type_id'],
                'starting_weight'           => $request['weight'],
                'current_weight'            => $request['weight'],
                'days'                      => $request['days'],
                'age'                       => $request['age'],
                'height'                    => $request['height'],
                'gender'                    => $request['gender'],
                'weight_goal'               => $request['weight_goal'],
                'role_id'                   => 3,
                'role_type'                 => 'user',
                'created_by'                => $authUser->id,
                'created_at'                => Carbon::now()->toDateTimeString(),
                'updated_at'                => Carbon::now()->toDateTimeString()
            ];

            if(!empty($request['new_pass'])){
                $data['password'] = bcrypt($request['new_pass']);
            }

            // Upload Franchise image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.users.image_path'), null, 'users-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['profile_image'] = $imageName;
                }
            }
            //-------------------
            
            $user = User::create($data);
            DB::commit();

        } catch (\Exception $e) {
            $user           = null;
            $errorMessage   = $e->getMessage();
            DB::rollback();
        }
        //------------
        if (!is_null($user)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'User']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.users.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'User']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.users.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Users.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $user = User::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Users' => route('nutritionPanel.users.index'),
            __('language.edit') => '',
        ];

        $mealTypes = MealType::where('status',1)->orderBy('id', 'DESC')->get();
        $productTypes = ProductType::where('status',1)->orderBy('id', 'DESC')->get();
        
        // Send view data
        $this->viewData['breadcrumb']   = $breadcrumb;
        $this->viewData['user']         = $user;
        $this->viewData['mealTypes']    = $mealTypes;
        $this->viewData['productTypes']    = $productTypes;

        return view('nutrition-panel.users.edit')->with($this->viewData);
    }

    /**
     * Update User.
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
        
        $userUpdate     = false;
        $errorMessage   = null;
        
        // Update User
        DB::beginTransaction();

        try {

            // Update User
            $user = User::where('id', dv($id))->first();

            $data = [
                'name'                      => $request['name'],
                'email'                     => $request['email'],
                'mobile_number'             => $request['mobile_number'],
                'date_of_birth'             => date('Y-m-d',strtotime($request['date_of_birth'])),
                'user_type'                 => $request['user_type'],
                'user_state'                => $request['user_state'],
                'coach_name'                => $request['coach_name'],
                'meal_type_id'              => $request['meal_type_id'],
                'product_type_id'           => $request['product_type_id'],
                'current_weight'            => $request['weight'],
                'age'                       => $request['age'],
                'height'                    => $request['height'],
                'gender'                    => $request['gender'],
                'weight_goal'               => $request['weight_goal'],
                // 'days'                      => $request['days'],
                'updated_at'                => Carbon::now()->toDateTimeString()
            ];

            if(!empty($request['new_pass'])){
                $data['password'] = bcrypt($request['new_pass']);
            }

            // Upload User image
            if ($request->hasFile('image'))
            {   
                // Remove old image
                if (!is_null($user->image)) {
                    delete_image(config('constants.users.image_path'), $user->image);
                }
                //-----------------
                $image = $this->uploadImage($request->file('image'), config('constants.users.image_path'), null, 'users-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['profile_image'] = $imageName;
                }
            }
            //-------------------
            
            $userUpdate = User::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $userUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }
        //------------

        if (!is_null($userUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'User']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.users.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'User']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.users.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = User::toggleStatus($request['ids']);

        DB::table('personal_access_tokens')->whereIn('tokenable_id',$request['ids'])->delete();
        
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
        $ids    = $request['ids'];
        $user   = User::whereIn('id', $ids)->delete();
        
        // Set response
        if ($user == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'User']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'User']),
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

            User::find($value[0])->update($data);
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
     * Check Franchise mobile.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created_at 28 Feb 2023
     */
    public function checkMobile(Request $request)
    {
        $status = false;
        if (!is_null($request->mobile_number)) {
            $user = User::where('mobile_number', $request['mobile_number'])->where('role_type', 'user')->first();

            if (!is_null($user)) {
                if ($request->filled('id') && $user->id == $request['id']) {
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $status = true;
            }
        }

        return response()->json($status, 200);
    }

    /**
     * Check Franchise email.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created_at 28 Feb 2023
     */
    public function checkEmail(Request $request)
    {
        $status = false;

        if (!is_null($request->email)) {
            $user = User::where('email', $request['email'])->where('role_type', 'user')->first();

            if (!is_null($user)) {
                if ($request->filled('id') && $user->id == $request['id']) {
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $status = true;
            }
        }

        return response()->json($status, 200);
    }

    /**
     * Edit User Quick.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function editUserQuick($id)
    {
        $auth_user = auth()->user();

        // Edit User Quick.
        $user = User::where('id', dv($id))->first();
        $mealTypes = MealType::where('status',1)->orderBy('id', 'DESC')->get();
        $productTypes = ProductType::where('status',1)->orderBy('id', 'DESC')->get();

        // Send view data
        $this->viewData['user'] = $user;
        $this->viewData['mealTypes'] = $mealTypes;
        $this->viewData['productTypes'] = $productTypes;

        return view('nutrition-panel.users.edit-user-quick')->with($this->viewData);
    }

    /**
     * Update User Quick.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function updateUserQuick(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $userUpdate     = false;
        $errorMessage   = null;
        
        // Update User
        DB::beginTransaction();

        try {

            // Update User
            $user = User::where('id', dv($id))->first();

            $data = [
                'user_type'                 => $request['user_type'],
                'user_state'                => $request['user_state'],
                'meal_type_id'              => $request['meal_type_id'],
                'product_type_id'           => $request['product_type_id'],
                'updated_at'                => Carbon::now()->toDateTimeString()
            ];
            
            $userUpdate = User::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $userUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }
        //------------

        // Set response
        if (!is_null($userUpdate)){
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Quick User']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Quick User']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * Add User Days.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function addUserDays($id)
    {
        $auth_user = auth()->user();

        // Add User Days
        $user = User::where('id', dv($id))->first();

        // Send view data
        $this->viewData['user'] = $user;

        return view('nutrition-panel.users.add-user-days')->with($this->viewData);
    }

    /**
     * Update User Days.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function updateUserDays(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $userUpdate     = false;
        $errorMessage   = null;
        
        // Update User
        DB::beginTransaction();

        try {
            $user           = User::where('id', dv($id))->first();
            $attendenceLogs = AttendanceLogs::where('user_id',$user->id)->orderBy('id','DESC')->first();

            if ($attendanceLogs) {
                $data = [
                    'user_id'       => $user->id,
                    'date'          => date('Y-m-d'),
                    'remark'        => 'Add User Days',
                    'days'          => $request['days'],
                    'message'       => $request['remark'],
                    'total_days'    => $request['days'],
                    'created_by'    => $authUser->id,
                ];
                
                AttendanceLogs::create($data);
            } else {
                $data = [
                    'user_id'       => $user->id,
                    'date'          => date('Y-m-d'),
                    'remark'        => 'Add User Days',
                    'days'          => $request['days'],
                    'message'       => $request['remark'],
                    'total_days'    => $attendenceLogs['total_days'] + $request['days'],
                    'created_by'    => $authUser->id,
                ];
                
                AttendanceLogs::create($data);
            }

            // if($request['payment_type'] == 'Pending'){
            //     User::where('id', dv($id))->decrement('due_amount', $request['amount']);
            // }

            // if($request['payment_type'] == 'Received' && $request['days'] == 0){
            //     User::where('id', dv($id))->increment('due_amount', $request['amount']);
            // }

            if($request['amount'] - $request['received_amount'] > 0){
                $request['payment_type'] = 'Pending';
            } else {
                $request['payment_type'] = 'Received';
            }

            $transaction = [
                'user_id'           => $user->id,
                'title'             => 'Add User Days',
                'total_amount'      => $request['amount'],
                'received_amount'   => $request['received_amount'],
                'due_amount'        => $request['amount'] - $request['received_amount'],
                'payment_type'      => $request['payment_type'],
                'created_by'        => $authUser->id,
            ];
            
            Transaction::create($transaction);

            User::where('id', dv($id))->increment('due_amount', ($transaction['due_amount']));
            $userUpdate = User::where('id', dv($id))->increment('days', $request['days']);

            // Notification Send
            $senderData   = User::where('id', 0)->first();
            $receiverData = User::where('id', dv($id))->first();

            if($senderData['name'] == ''){
                $senderData['name'] = 'Anonymous User';
            } else {
                $senderData['name'] = $senderData['name'];
            }

            if($receiverData['name'] == ''){
                $receiverData['name'] = 'Anonymous User';
            } else {
                $receiverData['name'] = $receiverData['name'];
            }

            $title              = 'Days Added';
            $notiMessage        = $receiverData['name'].', You’re all set '.$request['days'].' Days added.';
            $message            = $receiverData['name'].', You’re all set '.$request['days'].' Days added.';
            $notificationType   = 1;

            Notification::create([
                'user_id'             => $receiverData->id,
                'sender_id'           => $senderData->id,
                'data_id'             => '',
                'notification_title'  => $title,
                'notification_text'   => $notiMessage,
                'sender_name'         => $senderData['name'],
                'receiver_name'       => $receiverData['name'],
                'notification_type'   => $notificationType,
            ]);

            $user_id                = $receiverData->id;
            $notification_title     = $title;
            $notification_text      = $message;
            $sender_id              = $senderData->id;
            $notification_type      = $notificationType;
            $platform               = $receiverData->device_os;
            $fcm_token              = $receiverData->fcm_token;
            $data_id                = '';
            $sender_name            = $senderData['name'];
            $receiver_name          = $receiverData['name'];

            push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);

            DB::commit();
        } catch (\Exception $e) {
            $userUpdate = null;
            DB::rollback();
        }
        // ------------

        // Set response
        if (!is_null($userUpdate)){
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Add User Days']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Add User Days']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * Subtract User Days.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function subtractUserDays($id)
    {
        $auth_user = auth()->user();

        // Subtract User Days
        $user = User::where('id', dv($id))->first();

        // Send view data
        $this->viewData['user'] = $user;

        return view('nutrition-panel.users.subtract-user-days')->with($this->viewData);
    }

    /**
     * Update Subtract User Days.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function updateSubtractUserDays(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $userUpdate     = false;
        $errorMessage   = null;
        
        // Update User
        DB::beginTransaction();

        try {
            $user           = User::where('id', dv($id))->first();
            $attendenceLogs = AttendanceLogs::where('user_id',$user->id)->orderBy('id','DESC')->first();

            if ($attendanceLogs) {
                $data = [
                    'user_id'       => $user->id,
                    'date'          => date('Y-m-d'),
                    'remark'        => 'Substarct User Days',
                    'days'          => $request['days'],
                    'message'       => $request['remark'],
                    'total_days'    => $request['days'],
                    'created_by'    => $authUser->id,
                ];
                
                AttendanceLogs::create($data);
            } else {
                $data = [
                    'user_id'       => $user->id,
                    'date'          => date('Y-m-d'),
                    'remark'        => 'Substarct User Days',
                    'days'          => $request['days'],
                    'message'       => $request['remark'],
                    'total_days'    => $attendenceLogs['total_days'] - $request['days'],
                    'created_by'    => $authUser->id,
                ];
                
                AttendanceLogs::create($data);
            }

            $userUpdate = User::where('id', dv($id))->decrement('days', $request['days']);


            // Notification Send
            $senderData   = User::where('id', 0)->first();
            $receiverData = User::where('id', dv($id))->first();

            if($senderData['name'] == ''){
                $senderData['name'] = 'Anonymous User';
            } else {
                $senderData['name'] = $senderData['name'];
            }

            if($receiverData['name'] == ''){
                $receiverData['name'] = 'Anonymous User';
            } else {
                $receiverData['name'] = $receiverData['name'];
            }

            $title              = 'Days Subtract';
            $notiMessage        = $receiverData['name'].', '.$request['days'].' days are deducted from your subscription.';
            $message            = $receiverData['name'].', '.$request['days'].' days are deducted from your subscription.';
            $notificationType   = 1;

            Notification::create([
                'user_id'             => $receiverData->id,
                'sender_id'           => $senderData->id,
                'data_id'             => '',
                'notification_title'  => $title,
                'notification_text'   => $notiMessage,
                'sender_name'         => $senderData['name'],
                'receiver_name'       => $receiverData['name'],
                'notification_type'   => $notificationType,
            ]);

            $user_id                = $receiverData->id;
            $notification_title     = $title;
            $notification_text      = $message;
            $sender_id              = $senderData->id;
            $notification_type      = $notificationType;
            $platform               = $receiverData->device_os;
            $fcm_token              = $receiverData->fcm_token;
            $data_id                = '';
            $sender_name            = $senderData['name'];
            $receiver_name          = $receiverData['name'];

            push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);

            DB::commit();
        } catch (\Exception $e) {
            $userUpdate = null;
            DB::rollback();
        }
        //------------

        // Set response
        if (!is_null($userUpdate)){
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Subtract User Days']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Subtract User Days']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * View Weights list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function viewWeights($id)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'View Weights' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        // $breadcrumbButton[] = [
        //     'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
        //     'btn_link' => 'javascript:;',
        //     'btn_icon' => 'filter',
        //     'btn_text' => __('language.filter'),
        //     'attributes' => []
        // ];

        $user = User::where('id', dv($id))->first();

        $firstRecord = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where('weight','!=','')
            ->where("users.role_type", 'user')->where('type', 2)->where('user_id', $user['id'])->where("users.created_by", $authUser->id)
            ->orderBy('attendances.date','ASC')->first();

        $lastRecord = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where('weight','!=','')
            ->where("users.role_type", 'user')->where('type', 2)->where('user_id', $user['id'])->where("users.created_by", $authUser->id)
            ->orderBy('attendances.id','DESC')->first();

        $secondLastRecord = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where('weight','!=','')
            ->where("users.role_type", 'user')->where('type', 2)->where('user_id', $user['id'])->where("users.created_by", $authUser->id)
            ->skip(1)->orderBy('attendances.id','DESC')->first();

        $weights = Attendance::select('weight','date')
            ->where('user_id', $user['id'])
            ->where('type', 2)
            ->where('weight', '!=', '')
            ->orderBy('date', 'DESC')   // latest first
            ->limit(30)
            ->get()
            ->sortBy('date')            // convert to ASC
            ->values();

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['firstRecord'] = $firstRecord;
        $this->viewData['lastRecord'] = $lastRecord;
        $this->viewData['secondLastRecord'] = $secondLastRecord;
        $this->viewData['user'] = $user;
        $this->viewData['weights'] = $weights;
        $this->viewData['weightDates'] = $weights->pluck('date')->toArray();
        $this->viewData['weightValues'] = $weights->pluck('weight')->toArray();
        
        return view('nutrition-panel.users.view-weight')->with($this->viewData);
    }

    /**
     * Get Weights list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getViewWeights(Request $request)
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
            "user_id" => $request->user_id,
            "year" => $request->year,
            "date_range" => $request->date_range,
        );

        // Getting Weights Records
        $records_count  = Attendance::getViewWeights(null, null, $search, $filter, $sort);
        $records        = Attendance::getViewWeights($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name       = 'N/A';
                $weight     = 'N/A';
                $weight_image     = 'N/A';
                $date       = 'N/A';
                
                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->weight)){
                    $weight = $value->weight;
                }

                if(!empty($value->weight_image)){
                    $weight_image = '<a herf="#" data-url="' . route('nutritionPanel.users.viewWeightImage', ['id' => ev($value->id)]) . '" class="view-image cursor-pointer" title="View Image"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Image</div></a>';
                }

                if(!empty($value->date)){
                    $date = date("d-m-Y", strtotime($value->date));
                }

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "weight"            => $weight,
                    "weight_image"      => $weight_image,
                    "date"              => $date,
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
     * View Attendance list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function viewAttendence(Request $request, $id)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'View Attendance' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        // $breadcrumbButton[] = [
        //     'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
        //     'btn_link' => 'javascript:;',
        //     'btn_icon' => 'filter',
        //     'btn_text' => __('language.filter'),
        //     'attributes' => []
        // ];

        $user = User::where('id', dv($id))->first();

        $year = $request->year ?? date('Y'); 

        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear();

        $attendances = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.type', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where("users.role_type", 'user')
            ->where('type', 2)
            ->where('user_id', $user['id'])
            ->whereBetween('attendances.date', [$startDate, $endDate])
            ->orderBy('attendances.date','ASC')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['user'] = $user;
        $this->viewData['year'] = $year;
        $this->viewData['attendances'] = $attendances;
        
        return view('nutrition-panel.users.view-attendence')->with($this->viewData);
    }

    /**
     * View Weight Image.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function viewWeightImage($id)
    {
        $auth_user = auth()->user();

        // Get Weight Image
        $weightImage = Attendance::where('id', dv($id))->first();

        // Send view data
        $this->viewData['weightImage'] = $weightImage;

        return view('nutrition-panel.users.view-weight-image')->with($this->viewData);
    }

    /**
     * Details User.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function details(Request $request, $id)
    {
        $auth_user = auth()->user();
        $user = User::where('id', dv($id))->first();
 
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Users' => route('nutritionPanel.users.index'),
            'User Details' => '',
        ];

        $lastRecord = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('weight','!=','')
            ->where('type', 2)->where('user_id', $user['id'])->where("franchise_id", $auth_user->id)
            ->orderBy('attendances.id','DESC')->first();

        $maxWeight = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('weight','!=','')
            ->where('type', 2)->where('user_id', $user['id'])->where("franchise_id", $auth_user->id)
            ->orderBy('attendances.weight','DESC')->first();

        $minWeight = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('weight','!=','')
            ->where('type', 2)->where('user_id', $user['id'])->where("franchise_id", $auth_user->id)
            ->orderBy('attendances.weight','ASC')->first();
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['user'] = $user;
        $this->viewData['lastRecord'] = $lastRecord;
        $this->viewData['maxWeight'] = $maxWeight;
        $this->viewData['minWeight'] = $minWeight;
        
        return view('nutrition-panel.users.details')->with($this->viewData);
    }
}