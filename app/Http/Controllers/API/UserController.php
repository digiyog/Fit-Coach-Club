<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\UploadImage;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use \Kreait\Firebase\Messaging;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;


class UserController extends Controller
{
    use UploadImage, UploadFile;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['testNotification']);
    }

    /**
     * View Profile.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    */
    public function viewProfile(Request $request)
    {
        $responseCode = 200;
        $errorMessage = null;
        $userProfile  = null;
        $user = auth()->user();
        $weight = 0;
        $weight_image = 0;
        $weight_goal = 0;

        DB::beginTransaction();
        try {

            $franchiseInfo = User::where('id', $user->created_by)->first();

            $today  = Carbon::today();
            $exists = Attendance::where('user_id', $user['id'])->where('type',2)->whereDate('date', $today)->first();

            if($exists->weight != ''){
                $weight = 1;
            }

            if($exists->weight_image != ''){
                $weight_image = 1;
            }

            if($exists->weight_goal != ''){
                $weight_goal = 1;
            }

            if($request['user_id'] != ''){
                $userId = $request['user_id'];
            } else {
                $userId = $user->id;
            }

            if($request['user_id'] != ''){
                $userProfile = userInfo($request['user_id']);
            } else {
                $userProfile = userInfo($user->id);
            }

            DB::commit();
        } catch (\Exception $e) {
            $userProfile = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if (!is_null($userProfile)) {
            $bucket_base_url    = env('AWS_CloudFront_URL').'/';
            $response = [
                '_status' => true,
                '_message' => __('messages.profile_found'),
                '_qr_code' => $bucket_base_url. config('constants.users.image_path').'/'.$franchiseInfo['qr_code'],
                'weight' => $weight,
                'weight_image' => $weight_image,
                'weight_goal' => $weight_goal,
                '_data' => $userProfile,
            ];

        } else if(empty($userProfile) && is_null($errorMessage)) {

            $response = [
                '_status' => false,
                '_message' => __('messages.profile_not_found'),
                '_data' => null,
            ];

        } else {

            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];
        }

        return response()->json($response, $responseCode);
    }


    /**
     * Update Profile.
     *
     * @param  UpdateProfile  $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateProfile(Request $request)
    {
        $responseCode = 200;
        $errorMessage = null;
        $userProfile  = null;
        $profileImage = null;
        $user = auth()->user();

        DB::beginTransaction();
        try {

            // Get User
            $userProfile = User::where('id', $user->id)->select('id', 'name')->first();
            //---------

            if(!empty($userProfile)){

                // Upload Image
                if ($request->file('profile_image')) {
                    $file = $this->uploadImage($request->file('profile_image'), config('constants.users.image_path'));
                    $profileImage = $file['_data'];
                    $userProfile->profile_image = $profileImage;
                }
                //-------------
                
                if(isset($request['name']) && !empty($request['name'])) {
                    $userProfile->name = ucwords($request['name']);
                }

                if(isset($request['current_weight']) && !empty($request['current_weight'])) {
                    $userProfile->current_weight = $request['current_weight'];
                }

                if(isset($request['weight_goal']) && !empty($request['weight_goal'])) {
                    $userProfile->weight_goal = $request['weight_goal'];
                }

                if(isset($request['mobile_number']) && !empty($request['mobile_number'])) {
                    $userProfile->mobile_number = $request['mobile_number'];
                }

                $userProfile->save();
                
                // Create Activity Logs
                $logMessage = 'Profile Updated';
                set_activity_log($user->id, 'profile-updated', $logMessage, 'profile');
                //---------------------

            } else {
                $userProfile = null;
            }

            // Get User All Details
            $user = userInfo($user->id);
            //----------------------

            DB::commit();
        } catch (\Exception $e) {
            $userProfile = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if (!is_null($userProfile)) {

            $response = [
                '_status' => true,
                '_message'  => __('messages.user_profile_updated'),
                '_data' => $user,
            ];

        } else if(empty($userProfile) && is_null($errorMessage)) {

            $response = [
                '_status' => false,
                '_message' => __('messages.update_user_profile_failed'),
                '_data' => null,
            ];

        } else {

            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];
        }

        return response()->json($response, $responseCode);
    }

    /**
     * Change Password
     *
     * @param ChangePassword $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function changePassword(Request $request)
    {
        // Get users
        $user = auth()->user();
        //----------

        $errorMessage               = null;
        $responseCode               = 200;
        $currentPasswordMismatch    = false;
        $passwordSaved              = false;
        $newPasswordIsSame          = false;

        // Update user password
        DB::beginTransaction();
        try {

            if (Hash::check($request['current_password'], $user->password)) {
                if (!Hash::check($request['new_password'], $user->password)) {
                    $user->password = Hash::make($request['new_password']);
                    $passwordSaved = $user->save();
                } else {
                    $newPasswordIsSame = true;
                }
            } else {
                $currentPasswordMismatch = true;
            }

            DB::commit();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $passwordSaved = false;
            DB::rollback();
        }
        //---------------------

        // Set response
        if ($currentPasswordMismatch) {
            $response = [
                '_status' => false,
                '_message' => __('messages.current_password_mismatch'),
                '_data' => null,
            ];
        } else if ($newPasswordIsSame) {
            $response = [
                '_status' => false,
                '_message' => __('messages.new_password_is_same'),
                '_data' => null,
            ];
        } else if ($passwordSaved) {
            $response = [
                '_status' => true,
                '_message' => __('messages.user_password_updated'),
                '_data' => null,
            ];
        } else if ($errorMessage){

            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];

        } else {

            $response = [
                '_status' => false,
                '_message' => __('messages.password_reset_failed'),
                '_data' => null,
            ];
        }
        //-------------

        return response()->json($response, $responseCode);
    }

    /**
     * Update User Device Info.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateDeviceInfo(Request $request)
    {
        $responseCode = 200;
        $errorMessage = null;
        $user = auth()->user();

        DB::beginTransaction();
        try {

            $user->fcm_token            = $request['fcm_token'];
            $user->device_id            = $request['device_id'];
            $user->device_type          = $request['device_type'];
            $user->device_os            = $request['device_os'];
            $user->device_os_version    = $request['device_os_version'];
            $user->device_manufacturer  = $request['device_manufacturer'];
            $user->device_model         = $request['device_model'];
            $user->app_version          = $request['app_version'];
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            $user = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if (!empty($user)) {
            $response = [
                '_status' => true,
                '_message' => __('messages.device_info_update_success'),
                '_data' => null,
            ];
            
        } else if(empty($user) && is_null($errorMessage)) {
            $response = [
                '_status' => false,
                '_message' => __('messages.device_info_update_failed'),
                '_data' => null,
            ];

        } else {
            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Delete account
     *
     * @param deleteMyAccount $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function deleteAccount(Request $request)
    {
        $responseCode = 200;
        $errorMessage = null;
        $logMessage = null;
        $userUpdated = null;
        $errorMessage = null;
        $user = auth()->user();
        $bookingStatus = null;

        DB::beginTransaction();
        try {

            if(!empty($user)){

                // Delete Auth Token
                DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
                //------------------

                $data = [
                    'deleted_at' => Carbon::now()->toDateTimeString(),
                    'delete_reason' => $request['reason'],
                    'updated_by' => $user->id
                ];
    
                $userUpdated = User::where('id', $user->id)->update($data);

                // Create Activity Logs Message
                $logMessage = 'User account deleted';
                //---------------------

            } else {
                // Create Activity Logs Message
                $logMessage = 'User account cannot be deleted';
                //---------------------
            }

            // Create Activity Logs
            set_activity_log($user->id, 'user-account-delete', $logMessage, 'user');
            //---------------------

            DB::commit();
        } catch (\Exception $e) {
            $userUpdated = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if (!empty($userUpdated)) {
            $response = [
                '_status' => true,
                '_message' => __('messages.account_delete_success'),
                '_data' => null,
            ];
        } else if(empty($userUpdated) && is_null($errorMessage)) {
            $response = [
                '_status' => false,
                '_message' => __('messages.account_delete_failed'),
                '_data' => null,
            ];
        } else {
            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        $user->fcm_token = null;
        $user->save();

        // Logout user and set response
        if ($user->tokens()->delete()) {
            $response = [
                '_status' => true,
                '_message' => __('messages.logged_out'),
                '_data' => null,
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.logging_out_failed'),
                '_data' => null,
            ];
        }
        //-----------------------------

        return response()->json($response, 200);
    }










    

    public function updateNotification(Request $request)
    {
        if($request['notification_status'] == ''){
            $response = [
                '_status'  => false,
                '_message' => __('messages.notification_status_required'),
            ];
            return response()->json($response, 200);
        }

        $user = Auth::user();
        $updated = User::where('id', $user->id)->update(['notification_status' => $request['notification_status']]);

        if($updated) {
            $response = [
                '_status' => true,
                '_message' => __('messages.notification_status_update_success'),
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.notification_status_update_failed'),
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * Get Notifications.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getNotifications(Request $request)
    {
        $responseCode   = 200;
        $notifications  = null;
        $errorMessage   = null;
        $user           = auth()->user();
        $limit          = config('constants.OTHER_RECORD.limit');
        $unread_notification = Notification::where('user_id', $user->id)->where('status', 0)->count();

        DB::beginTransaction();
        try {

            $userId = $user->id;

            // Get User Notifications
            $notifications = Notification::orderBy('id','DESC')
            ->where(function ($query) use ($request ,$user) {
                $query->where('user_id', $user->id);
            })
            ->paginate(15);
            //--------------------
            Notification::where('user_id', $user->id)->update(['status' => 1]);

            DB::commit();
        } catch (\Exception $e) {
            $notifications = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if ($notifications[0]) {
            $response = [
                '_status' => true,
                '_message' => __('messages.records_found', ['record' => 'Notification']),
                'unread_notification' => $unread_notification,
                '_data' => $notifications
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_not_found', ['record' => 'Notification']),
                '_data' => null,
            ];
        }

        return response()->json($response, $responseCode);
    }

    /**
     * Unread Notification.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function unreadNotification(Request $request)
    {
        $responseCode = 200;
        $notification = null;
        $errorMessage = null;
        $user = auth()->user();

        DB::beginTransaction();
        try {

            // Update notification status
            $notification = Notification::where('user_id', $user->id)
            ->update(['read_status' => config('notification_constants.notification_read_status.READ.value')]);
            //----------------------------

            DB::commit();
        } catch (\Exception $e) {
            $notification = null;
            $errorMessage = $e->getMessage();
            DB::rollBack();
        }

        // Set response
        if (!is_null($notification)) {

            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Notification']),
                '_data' => null,
            ];

        } else if(empty($notification) && is_null($errorMessage)) {

            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Notification']),
                '_data' => null,
            ];

        } else {

            // Set response code
            $responseCode = 500;
            //------------------

            $response = [
                '_status' => false,
                '_message' => __('messages.something_went_wrong'),
                '_data' => null,
            ];
        }

        return response()->json($response, $responseCode);

    }

    public function testNotification(Request $request){

        $data = array(
            'title' => 'Kiya bhai kiya barosa nahi hai kiya !!',
            'body' => 'Kiya bhai kiya barosa nahi hai kiya !!',
            'description' => 'Kiya bhai kiya barosa nahi hai kiya !!'
        );

        $data_json = json_encode($data, true);

        // Initialize Firebase
        $firebase  = (new Factory)->withServiceAccount(app_path().'/fit-coach-club-firebase-adminsdk-fbsvc-9bb58745c0.json');
        $messaging = $firebase->createMessaging();

        $tokenCollection    = collect([$request['fcm_token']]);
        $chunkArray         = $tokenCollection->chunk(100)->toArray();

        foreach($chunkArray as $chunk) {
            foreach ($chunk as $fcmToken) {

                if($request['type'] == 1){
                    $message = CloudMessage::fromArray([
                        'data' => $data,
                        'token' => $fcmToken,
                        'android' => [
                            'priority' => 'HIGH',
                        ],
                        'apns' => [
                            'headers' => [
                                'apns-priority' => '10',
                            ],
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                    'badge' => 1,
                                ],
                            ],
                        ],
                    ]);
                } else {
                    $message = CloudMessage::fromArray([
                        'notification' => $data,
                        'data' => [
                            'data' => $data_json,
                        ],
                        'token' => $fcmToken,
                        'android' => [
                            'priority' => 'HIGH',
                        ],
                        'apns' => [
                            'headers' => [
                                'apns-priority' => '10',
                            ],
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                    'badge' => 1,
                                ],
                            ],
                        ],
                    ]);
                }

                try {
                    $sendReport = $messaging->send($message);
                } catch (\Kreait\Firebase\Exception\MessagingException $e) {
                    echo 'Error sending message: ' . $e->getMessage();
                }
            }
        }
    }
}