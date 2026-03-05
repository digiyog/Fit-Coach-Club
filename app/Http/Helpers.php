<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyProfile;
use App\Models\Configuration;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Models\User;
use Nexmo\Laravel\Facade\Nexmo;
use App\Models\ThirdPartyIntegration;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Kreait\Laravel\Firebase\Facades\Firebase;
use \Kreait\Firebase\Messaging;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;

if (!function_exists('p')) {
    /**
     * Short function for print data
     *
     * @param Array or Object or string
     * @return formatted array or object
     *
     * @author Pratyush Bharti
     * @created_at 24 July 2019
     */
    function p($p, $exit = 1)
    {
        echo '<pre>';
        print_r($p);
        echo '</pre>';
        if ($exit == 1) {
            exit;
        }
    }
}

if (!function_exists('ev')) {
    /**
     * Encrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    function ev($value)
    {
        return encrypt($value);
    }
}

if (!function_exists('dv')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    function dv($value)
    {
        $decrypted_value = null;

        try {
            $decrypted_value = decrypt($value);
        } catch (\Exception $e) {
            abort(404);
        }

        return $decrypted_value;
    }
}

function get_decrypted_value($key, $decrypt = false)
{
    $decrypted_key = null;
    if (!empty($key)) {
        if ($decrypt == true) {
            $key = Crypt::decrypt($key);
        }
        $decrypted_key = $key;
    }
    return $decrypted_key;
}

if (!function_exists('get_image_url')) {
    /**
     * Get image url.
     *
     * @param  string  $value
     * @return string  $url
     *
     * @author Sumit
     * @created_at 19 July 2019
     */
    function get_image_url($path, $name)
    {
        // // // File system
        // $file_system = config('filesystems.default');
        // // //------------

        // // File system
        // // $file_system = config('filesystems.root_public');
        // //------------
        // $url = $file_system.'/'.$path . str_replace(' ', '%20', $name);
        // /* $arrContextOptions=array(
        //   "ssl"=>array(
        //         "verify_peer"=>false,
        //         "verify_peer_name"=>false,
        //     ),
        // );
        // if(file_get_contents($url,false, stream_context_create($arrContextOptions))) {
        //     return $url;
        // } else {
        //     return null;
        // } */

        // p(Storage::disk($file_system));

        // if(!is_null($name) && Storage::disk($file_system)->exists($path . $name)) 
        // {
        //     return Storage::disk($file_system)->url($path . $name);
        // } 
        // else 
        // {
        //     return null;
        // }

        // File system
        $file_system = env('AWS_CloudFront_URL');
        //------------
        $url = $file_system . '/' . $path . str_replace(' ', '%20', $name);
        // p($url);
        // $arrContextOptions=array(
        //   "ssl"=>array(
        //         "verify_peer"=>false,
        //         "verify_peer_name"=>false,
        //     ),
        // );

        // if(file_get_contents($url,false, stream_context_create($arrContextOptions))) {
        return $url;
        // } else {
        //     return null;
        // }
    }
}

if (!function_exists('delete_image')) {
    /**
     * Delete image.
     *
     * @param  string  $value
     * @return boolean
     *
     * @author Sumit
     * @created_at 19 July 2019
     */
    function delete_image($path, $name)
    {
        // File system
        $file_system = config('filesystems.default');
        //------------

        if (Storage::disk($file_system)->exists($path . $name)) {
            $deleted = Storage::disk($file_system)->delete($path . $name);

            if (Storage::disk($file_system)->exists($path . 'thumb/' . $name)) {
                $deletedThumb = Storage::disk($file_system)->delete($path . 'thumb/' . $name);
            }

            return $deleted;
        } else {
            return false;
        }
    }
}

if (!function_exists('show_user_image')) {
    /**
     * Delete image.
     *
     * @param  string  $value
     * @return boolean
     *
     * @author Sumit
     * @created_at 19 July 2019
     */
    function show_user_image($image = null, $name = null)
    {
        $url = get_image_url(config('constants.users.image_path'), $image);

        if (!is_null($url)) {
            $image = '<img src="' . $url . '" style="height:30px; width:30px;"/>';
        } else {
            $image = '<img src="' . Avatar::create($name)->toBase64() . '"/>';
        }

        return $image;
    }
}

if (!function_exists('show_user_image_large')) {
    /**
     * Delete image.
     *
     * @param  string  $value
     * @return boolean
     *
     * @author Sumit
     * @created_at 19 July 2019
     */
    function show_user_image_large($image = null, $name = null)
    {
        $url = get_image_url(config('constants.users.image_path'), $image);

        if (!is_null($url)) {
            $image = '<img src="' . $url . '" style="height:80px; width:80px;"/>';
        } else {
            $image = '<img src="' . Avatar::create($name)->toBase64() . '" style="height:80px; width:80px;"/>';
        }

        return $image;
    }
}

if (!function_exists('create_select_options')) {
    /**
     * Create select option.
     *
     * @param  mixed  $data, $value, $key, $default
     * @return array
     *
     * @author Sumit
     * @created_at 22 July 2019
     */
    function create_select_options($data, $value, $key = null, $default = null)
    {
        // p($default);
        $options = [];

        // Create options
        if ($data instanceof Illuminate\Database\Eloquent\Collection) {
            $options = array_column($data->toArray(), $value, $key);
        } else {
            $options = array_column($data, $value, $key);
        }
        //---------------

        // Set default option
        if (!is_null($default)) {
            $options = Arr::prepend($options, $default, '');
        }
        return $options;
    }
}

if (!function_exists('create_select_groups_options')) {
    /**
     * Create select groups option.
     *
     * @param  mixed  $data, $value, $key, $default
     * @return array
     *
     * @author Sumit
     * @created_at 22 July 2019
     */
    function create_select_groups_options($data, $child_column, $value, $key = null, $default = null)
    {
        $options = [];

        // Create options
        if ($data instanceof Illuminate\Database\Eloquent\Collection) {
            foreach ($data as $_value) {
                $options[$_value[$value]] = array_column($_value[$child_column]->toArray(), $value, $key);
            }
        } else {
            foreach ($data as $_value) {
                $options[$_value[$value]] = array_column($_value[$child_column], $value, $key);
            }
        }
        //---------------

        // Set default option
        if (!is_null($default)) {
            $options = Arr::prepend($options, $default, '');
        }
        //-------------------

        return $options;
    }
}

if (!function_exists('add_blank_option')) {
    /**
     * Add Blank Option.
     *
     * @author Khushbu
     * @created_at 11 Jan 2020
     */
    function add_blank_option($arr, $option)
    {
        $arr_option = array();
        if (!empty($option)) {
            $arr_option[''] = $option;
        } else {
            $arr_option[''] = '';
        }
        // operator on array
        $result = $arr_option + $arr;

        return $result;
    }
}

if (!(function_exists('get_file_url'))) {
    /**
     * Get file url.
     *
     * @param  string  $value
     * @return string  $url
     *
     * @author Rajesh
     * @created_at 11 Sep 2021
     */
    function get_file_url($filepath)
    {
        // File system
        $file_system = env('AWS_CloudFront_URL');
        //------------
        $url = $file_system . '/' . str_replace(' ', '%20', $filepath);

        if (file_get_contents($url)) {
            return $url;
        } else {
            return null;
        }
    }
}

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('get_company_profile')) {

    /**
     * Get company profile details.
     *
     * @author Sumit
     * @created_at 24 Apr 2020
     */
    function get_company_profile()
    {
        $company =  CompanyProfile::get();
        return $company;
    }
}

if (!function_exists('get_configurations')) {

    /**
     * Get Configurations
     *
     * @author Sumit
     * @created_at 28 Dec 2021
     * 
     * @param array $configNames
     */
    function get_configurations($configNames = [])
    {
        $configurations = null;

        if (count($configNames) > 0) {
            // Get selected name and values
            $configurations = Configuration::select('id', 'config_name', 'config_value')
                ->where(function ($query) use ($configNames) {
                    foreach ($configNames as $key => $value) {
                        $query->orWhere('config_name', $value);
                    }
                })->get();
        } else {
            $configurations = Configuration::select('id', 'config_name', 'config_value')->get();
        }

        return $configurations;
    }
}

if (!function_exists('update_configurations_by_name')) {

    /**
     * Update configurations by config name column
     *
     * @author Rajesh
     * @created_at 2 Feb 2022
     */
    function update_configurations_by_name($configurationsData = [])
    {
        $configurationUpdate = null;
        $authUser = auth()->user();

        try {
            DB::beginTransaction();

            // Updating by keys (config_name) and values (config_value)
            if (count($configurationsData) > 0) {
                foreach ($configurationsData as $key => $value) {
                    $configuration = Configuration::where('config_name', $key)->first();

                    $data = [
                        'config_name' => $key,
                        'config_value' => $value,
                        'updated_by' => $authUser->id,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ];

                    if (empty($configuration->created_by)) {
                        $data['created_by'] = $authUser->id;
                    }

                    if (empty($configuration->created_at)) {
                        $data['created_at'] = Carbon::now()->toDateTimeString();
                    }
                    if (empty($configuration)) {
                        $configurationUpdate = Configuration::create($data);
                    } else {
                        $configurationUpdate = Configuration::where('config_name', $key)->update($data);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            $configurationUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }

        return $configurationUpdate;
    }
}

if (!function_exists('set_activity_log')) {

    /**
     * Set activity log
     *
     * @author Rajesh
     * @param integer $userId (activity done for user)
     * @param string $activityType
     * @param string $message (activity message)
     * @created_at 8 Jan 2022
     */
    function set_activity_log($userId, $activityType, $message, $moduleName = null)
    {
        $authUser = auth()->user();

        $currentDateTime = Carbon::now();
        //--------------

        $logData = [
            'user_id' => $userId ?? null,
            'activity_type' => $activityType ?? null,
            'message' => $message ?? null,
            'activity_module' => $moduleName ?? null,
            'created_by' => $authUser ? $authUser->id : $userId,
            'updated_by' => $authUser ? $authUser->id : $userId,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ];
        $logResult = ActivityLog::create($logData);

        return $logResult;
    }
}

function callAPI($apiURL, $requestParamList, $token=false)
{
    $jsonResponse = "";
    $responseParamList = array();
    $JsonData = json_encode($requestParamList);
    $postData = 'JsonData=' . urlencode($JsonData);
    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $JsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        // 'Content-Type: application/json',
        // 'Authorization: Bearer '.$token,
        'Content-Length: ' . strlen($JsonData))
    );
    $jsonResponse = curl_exec($ch);
    $responseParamList = json_decode($jsonResponse, true);
    // p($responseParamList);
    // return $responseParamList;
}

function get_extension($file_name) {
    $parts = pathinfo($file_name);
    if(isset($parts['extension'])) {
        return $parts['extension'];
    } else {
        return 'No extension found';
    }
}

function formatString($input) {
    // Remove spaces
    $noSpaces = str_replace('_', ' ', $input);
    // Capitalize the first letter of each word
    $formattedString = ucwords($noSpaces);
    return $formattedString;
}

function userInfo($user_id){

    $request['user_id'] = $user_id;

    $userProfile = User::where('id',$request['user_id'])->select('id', 'user_type', 'name', 'fcm_token', 'email', 'mobile_number', 'profile_image', 'coach_name', 'days', 'due_amount', 'current_weight', 'weight_goal', 'created_at')->first();

    return $userProfile;
}

if (!function_exists('push_notification')) {
    function push_notification($user_id = null, $title = null, $message = null, $sender_id = null, $type = null, $fcm_token = null, $data_id = null, $sender_name = null, $receiver_name = null, $platform= null)
    {
        $data = array(
            'title'             => $title,
            'body'              => strip_tags($message),
            'description'       => $message,
            'notification_type' => $type,
            'user_id'           => $user_id,
            'sender_id'         => $sender_id,
            'data_id'           => $data_id,
            'sender_name'       => $sender_name,
            'receiver_name'     => $receiver_name,
        );

        $data_json = json_encode($data, true);

        if($fcm_token != ''){
            // Initialize Firebase
            $firebase  = (new Factory)->withServiceAccount(app_path().'/fit-coach-club-firebase-adminsdk-fbsvc-9bb58745c0.json');
            $messaging = $firebase->createMessaging();

            $tokenCollection    = collect([$fcm_token]);
            $chunkArray         = $tokenCollection->chunk(100)->toArray();

            foreach($chunkArray as $chunk) {
                foreach ($chunk as $fcmToken) {
                    try {
                        if ($platform == 'Android') {
                            $message = CloudMessage::fromArray([
                                'notification' => $data,
                                'data'         => $data,
                                'token'        => $fcmToken,
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
                            $sendReport = $messaging->send($message);
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
                            $sendReport = $messaging->send($message);
                        }
                    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
                        // echo 'Error sending message: ' . $e->getMessage();
                    }
                }
            }
        }   
    }
}