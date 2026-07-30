<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Http\Traits\UploadImage;
use App\Http\Requests\AdminPanel\ChangePassword;
use Illuminate\Support\Facades\Hash;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\SendNotification;
use Storage;

class ProfileController extends Controller
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
     * Edit Confifuration.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Rajesh
     * @created 4 Feb 2022
     */
    public function index(Request $request)
    {
        // Get logged in admin user
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            __('language.profile') => '',
        ];

        // Getting timezone list for dropdown
        $timeZoneList = \DateTimeZone::listIdentifiers();

        // Get Countries ISO
        // $countriesIso = Country::select('id', 'name', 'iso')->active()->get();
        //----------

        // Get Country Dial Code
        // $selectedCountryCode = Country::select('id', 'iso')->where('phonecode', $authUser->country_code)->first();
        //----------
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['timeZoneList'] = $timeZoneList;
        $this->viewData['authUser'] = $authUser;
        // $this->viewData['countriesIso'] = $countriesIso;
        // $this->viewData['selectedCountryCode'] = $selectedCountryCode;

        return view('admin-panel.users.admin.edit')->with($this->viewData);
    }

    /**
     * Update Profile.
     *
     * @return mixed
     *
     * @author Rajesh
     * @created 4 Feb 2022
     */
    public function update(Request $request)
    {   
        // Get user
        $authUser = auth()->user();
        //----------

        $profileUpdate = false;
        $errorMessage = null;
        
        DB::beginTransaction();
        try {

            // Set data
            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile_number' => $request['mobile_number'],
                'updated_by' => $authUser->id,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            
            // Upload salon logo and add to data array
            if ($request->hasFile('profile_image'))
            {
                // Remove old image
                if (!is_null($authUser->profile_image)) {
                    delete_image(config('constants.users.image_path'), $authUser->profile_image);
                }
                //-----------------
                
                $image = $this->uploadImage($request->file('profile_image'), config('constants.users.image_path'), null, 'admin-profile-', 100);
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['profile_image'] = $imageName;
                }
            }
            //-------------------
            $profileUpdate = $authUser->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $profileUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Profile update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($profileUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Profile']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.profile')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Profile']),
                '_type' => 'error',
            ];
            //-----------------
            
            return redirect()->route('adminPanel.profile.update', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Update Profile Password.
     *
     * @return mixed
     *
     * @author Rajesh
     * @created 4 Feb 2022
     */
    public function updatePassword(ChangePassword $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        if(!(Hash::check($request->get('current_password'), $authUser->password)))
        {
            return redirect()->back()->withErrors(__('messages.password_not_matched'));
        }

        $profileUpdate = false;
        $errorMessage = null;
        
        DB::beginTransaction();
        try {

            // Set data
            $data = [
                'updated_by' => $authUser->id,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            
            if(!empty($request['new_password']))
            {
                $data['password'] = bcrypt($request['new_password']);
            }

            $profileUpdate = $authUser->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $profileUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Profile update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($profileUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Profile password']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.profile')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Profile password']),
                '_type' => 'error',
            ];
            //-----------------
            
            return redirect()->route('adminPanel.profile.update', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Check mobile.
     *
     * @return boolean
     *
     * @author Rajesh
     * @created_at 4 Feb 2022
     */
    public function checkMobile(Request $request)
    {
        $authUser = auth()->user();
        $status = false;

        if (!is_null($request->mobile_number)) 
        {
            $user = User::where('mobile_number', $request['mobile_number'])->first();

            if (!is_null($user)) {
                if ($user->id == $authUser->id) {
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
     * Check email.
     *
     * @return boolean
     *
     * @author Rajesh
     * @created_at 4 Feb 2022
     */
    public function checkEmail(Request $request)
    {
        $authUser = auth()->user();
        $status = false;

        if (!is_null($request->email)) 
        {
            $user = User::where('email', $request['email'])->first();

            if (!is_null($user)) {
                if ($user->id == $authUser->id) {
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
     * Get logs list.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 11 Feb 2022
     */
    public function getHistoryNotes(Request $request, $userId, $salonType = null)
    {
        $auth_user = auth()->user();
        
        // Ajax Post Parameters from Table
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "filter" => $request->filter,
            "filter_status" => $request->status_filter,
            "filter_doctype" => $request->document_type_filter
        );
        
        // Get documents list
        $records_count = User::GetHistoryNotes(null, null, $search, $filter, $sort, $userId);
        $records = User::GetHistoryNotes($limit, $start, $search, $filter, $sort, $userId);

        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $userName = 'N/A';
                $userNameActivityBy = 'N/A';
                $description = 'N/A';
                $createdAt = '';
                
                // Preparing Data
                $serial = ($key + 1);
                $userName = $value->user->name ?? $userName;
                $userNameActivityBy = $value->user_activity_by->name ?? $userNameActivityBy;
                $description = $value->message ?? $description;
                $createdAt = Carbon::parse($value->created_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A');
                
                // Array Data
                $arr_data[] = array(
                    "serial" => $serial,
                    "id" => $value->id,
                    "user_name" => $userName,
                    "user_name_activity_by" => $userNameActivityBy,
                    "description" => $description,
                    "created_at" => $createdAt
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );
        return json_encode($response);
    }


    /**
     * Get followings list.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 11 Feb 2022
     */
    public function getFollowings(Request $request, $userId)
    {
        $auth_user = auth()->user();
        
        // Ajax Post Parameters from Table
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            // No Filters
        );
        
        // Get referrals list
        $records_count = User::GetFollowings(null, null, $search, $filter, $sort, $userId);
        $records = User::GetFollowings($limit, $start, $search, $filter, $sort, $userId);

        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $detail = null;
                $followingUserName = 'N/A';
                $followingStatus = 'N/A';
                $followingDate = 'N/A';
                $createdAt = '';
                $status = '';
                
                // Preparing Data
                $serial = ($key + 1);
                $createdAt = Carbon::parse($value->created_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A');

                if(!(empty($value->following_users->name)))
                {
                    if($value->following_users->role_name == config('constants.users.roles.STYLA.type')){
                        $followingUserName = '<a href="'.route('adminPanel.stylas.getStylaDetail', ['id' => ev($value->following_user_id)]).'">'.$value->following_users->name.'</a>';
                    }
                    else if($value->following_users->role_name == config('constants.users.roles.STYLIST.type')){
                        $followingUserName = '<a href="'.route('adminPanel.stylists.getStylistDetail', ['id' => ev($value->following_user_id)]).'">'.$value->following_users->name.'</a>';
                    }
                    else if($value->following_users->role_name == config('constants.users.roles.FREELANCER.type')){
                        $followingUserName = '<a href="'.route('adminPanel.freelancers.getFreelancerDetail', ['id' => ev($value->following_user_id)]).'">'.$value->following_users->name.'</a>';
                    }
                    else if($value->following_users->role_name == config('constants.users.roles.SALON.type')){
                        $followingUserName = '<a href="'.route('adminPanel.salons.getSalonDetail', ['id' => ev($value->following_user_id)]).'">'.$value->following_users->name.'</a>';
                    }
                    else if($value->following_users->role_name == config('constants.users.roles.PREMIUM_SALON.type')){
                        $followingUserName = '<a href="'.route('adminPanel.salons.getSalonDetail', ['id' => ev($value->following_user_id), 'type' => $value->following_users->role_name]).'">'.$value->following_users->name.'</a>';
                    }
                    else{
                        $followingUserName = $value->following_users->name ?? $followingUserName;
                    }
                }
                
                $email = $value->following_users->email ?? $email;
                $mobile = $value->following_users->mobile_number ?? $mobile;

                if($followingUserName != ''){
                    $detail = $followingUserName;
                }

                if($email != ''){
                    if($detail != '')
                    {
                        $detail .= '<br/>'.$email;
                    }
                    else
                    {
                        $detail .= $email;
                    }
                }

                if($mobile != ''){
                    if($detail != '')
                    {
                        $detail .= '<br/>'.$mobile;
                    }
                    else
                    {
                        $detail .= $mobile;
                    }
                }

                if ($value->status == 1)
                {
                    $status = '<label class="badge badge-warning">Block</label> &nbsp;';
                } 
                else 
                {
                    $status = '<label class="badge badge-success">Unblock</label> &nbsp;';
                }
                
                // Array Data
                $arr_data[] = array(
                    "serial" => $serial,
                    "id" => $value->id,
                    "status" => $status,
                    "detail" => $detail,
                    "following_user_name" => $followingUserName,
                    "created_at" => $createdAt
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );
        return json_encode($response);
    }

    /**
     * Get followers list.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 11 Feb 2022
     */
    public function getFollowers(Request $request, $userId)
    {
        $auth_user = auth()->user();
        
        // Ajax Post Parameters from Table
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            // No Filters
        );
        
        // Get referrals list
        $records_count = User::GetFollowers(null, null, $search, $filter, $sort, $userId);
        $records = User::GetFollowers($limit, $start, $search, $filter, $sort, $userId);

        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $detail = null;
                $followerUserName = 'N/A';
                $followerStatus = 'N/A';
                $followerDate = 'N/A';
                $createdAt = '';
                $status = '';
                
                // Preparing Data
                $serial = ($key + 1);
                $createdAt = Carbon::parse($value->created_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A');

                if(!(empty($value->follower_users->name)))
                {
                    if($value->follower_users->role_name == config('constants.users.roles.STYLA.type')){
                        $followerUserName = '<a href="'.route('adminPanel.stylas.getStylaDetail', ['id' => ev($value->follower_user_id)]).'">'.$value->follower_users->name.'</a>';
                    }
                    else if($value->follower_users->role_name == config('constants.users.roles.STYLIST.type')){
                        $followerUserName = '<a href="'.route('adminPanel.stylists.getStylistDetail', ['id' => ev($value->follower_user_id)]).'">'.$value->follower_users->name.'</a>';
                    }
                    else if($value->follower_users->role_name == config('constants.users.roles.FREELANCER.type')){
                        $followerUserName = '<a href="'.route('adminPanel.freelancers.getFreelancerDetail', ['id' => ev($value->follower_user_id)]).'">'.$value->follower_users->name.'</a>';
                    }
                    else if($value->follower_users->role_name == config('constants.users.roles.SALON.type')){
                        $followerUserName = '<a href="'.route('adminPanel.salons.getSalonDetail', ['id' => ev($value->follower_user_id)]).'">'.$value->follower_users->name.'</a>';
                    }
                    else if($value->follower_users->role_name == config('constants.users.roles.PREMIUM_SALON.type')){
                        $followerUserName = '<a href="'.route('adminPanel.salons.getSalonDetail', ['id' => ev($value->follower_user_id), 'type' => $value->follower_users->role_name]).'">'.$value->follower_users->name.'</a>';
                    }
                    else{
                        $followerUserName = $value->follower_users->name ?? $followerUserName;
                    }
                }
                
                $email = $value->follower_users->email ?? $email;
                $mobile = $value->follower_users->mobile_number ?? $mobile;

                if($followerUserName != ''){
                    $detail = $followerUserName;
                }

                if($email != ''){
                    if($detail != '')
                    {
                        $detail .= '<br/>'.$email;
                    }
                    else
                    {
                        $detail .= $email;
                    }
                }

                if($mobile != ''){
                    if($detail != '')
                    {
                        $detail .= '<br/>'.$mobile;
                    }
                    else
                    {
                        $detail .= $mobile;
                    }
                }

                if ($value->status == 1)
                {
                    $status = '<label class="badge badge-warning">Block</label> &nbsp;';
                } 
                else 
                {
                    $status = '<label class="badge badge-success">Unblock</label> &nbsp;';
                }
                
                // Array Data
                $arr_data[] = array(
                    "serial" => $serial,
                    "id" => $value->id,
                    "status" => $status,
                    "detail" => $detail,
                    "following_user_name" => $followerUserName,
                    "created_at" => $createdAt
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );
        return json_encode($response);
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Rajesh
     * @created 11 Feb 2022
     */
    public function changePassword(Request $request)
    {
        // Get logged in admin user
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            __('language.change_password') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;        
        $this->viewData['authUser'] = $authUser;
        
        return view('admin-panel.users.admin.change-password')->with($this->viewData);
    }

    /**
     * Check salon mobile.
     *
     * @return boolean
     *
     * @author Rajesh
     * @created_at 12 May 2022
     */
    public function checkSalonMobile(Request $request)
    {
        $status = false;

        if (!is_null($request->company_mobile_number)) 
        {
            $user = User::where('mobile_number', $request['company_mobile_number']);

            if($request['role_type'] == config('constants.users.roles.PREMIUM_SALON.type')){
                $user->where('role_name', config('constants.users.roles.PREMIUM_SALON.type'));
            }
            else{
                $user->where('role_name', config('constants.users.roles.SALON.type'));
            }
            $user = $user->first();

            if (!is_null($user)) {
                if ($request->filled('user_id') && $user->id == dv($request['user_id'])) {
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
}
