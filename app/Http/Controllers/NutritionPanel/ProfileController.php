<?php

namespace App\Http\Controllers\NutritionPanel;

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
     * Edit Profile.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Rajesh
     * @created 4 Feb 2022
     */
    public function index(Request $request)
    {
        // Get logged in nutrition user
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            __('language.profile') => '',
        ];

        // Getting timezone list for dropdown
        $timeZoneList = \DateTimeZone::listIdentifiers();
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['timeZoneList'] = $timeZoneList;
        $this->viewData['authUser'] = $authUser;
        // $this->viewData['countriesIso'] = $countriesIso;
        // $this->viewData['selectedCountryCode'] = $selectedCountryCode;

        return view('nutrition-panel.users.admin.edit')->with($this->viewData);
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

            // Upload qr Code
            if ($request->hasFile('qr_code'))
            {
                // Remove old image
                if (!is_null($authUser->qr_code)) {
                    delete_image(config('constants.users.image_path'), $authUser->qr_code);
                }
                //-----------------
                
                $image = $this->uploadImage($request->file('qr_code'), config('constants.users.image_path'), null, 'qr-code-', null);
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['qr_code'] = $imageName;
                }
            }
            //-------------------

            $profileUpdate = $authUser->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $profileUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Nutrition Profile Error: ' . $e->getMessage());
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

            return redirect()->route('nutritionPanel.profile')->with(['notification' => $notification]);
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
            
            return redirect()->route('nutritionPanel.profile.update', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
            \Log::error('Nutrition Profile Error: ' . $e->getMessage());
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

            return redirect()->route('nutritionPanel.profile')->with(['notification' => $notification]);
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
            
            return redirect()->route('nutritionPanel.profile.update', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
            $user = User::where('mobile_number', $request['mobile_number'])->where('role_type','franchise')->first();

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
            $user = User::where('email', $request['email'])->where('role_type','franchise')->first();

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
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            __('language.change_password') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;        
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.users.admin.change-password')->with($this->viewData);
    }
}
