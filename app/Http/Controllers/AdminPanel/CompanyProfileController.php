<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyProfileController extends Controller
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
     * Edit profile.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Mukesh
     * @created_at 24 Jan 2023
     */
    public function index()
    {
        $auth_user         = auth()->user();
        $companyProfile    =  CompanyProfile::first();

       // Adding breadcrumb array
       $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Company Profile' => route('adminPanel.company-profile.index'),
            __('language.update') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['companyProfile'] = $companyProfile;

        return view('admin-panel.company-profile.index')->with($this->viewData);
    }

    /**
     * Update profile.
     *
     * @return mixed
     *
     * @author Mukesh
     * @created_at 24 Jan 2023
     */
    public function update(Request $request)
    {
        $companyProfile = false;
        $user           = auth()->user();
        $companyProfile =  CompanyProfile::first();

        $logo_image = $companyProfile->header_logo_image;
        $footer_logo_image = $companyProfile->footer_logo_image;
        $icon_image = $companyProfile->fab_icon_image;

        // Upload logo image
        if ($request->hasFile('header_logo_image')) 
        {
            // Remove old image
            if (!is_null($companyProfile->header_logo_image)) {
                delete_image(config('constants.company_profile.image-path'), $companyProfile->header_logo_image);
            }
            //-----------------
            $file = $this->uploadImage($request->file('header_logo_image'), config('constants.company_profile.image_path'));
   
            if ($file['_status']) {
                $logo_image = $file['_data'];
                $data['header_logo_image'] = $logo_image;
            }
        }
        //------------------

        DB::beginTransaction();

        // Update user
        try {

            // Set data
            $data = [
                'header_logo_image' => $logo_image,
                'name'              => $request['name'],
                'email'             => $request['email'],
                'phone_no'          => $request['phone_no'],
                'address'           => $request['address'],
                'facebook_link'     => $request['facebook_link'],
                'twitter_link'      => $request['twitter_link'],
                'instagram_link'    => $request['instagram_link'],
                'linkdin_link'      => $request['linkdin_link'],
                'whatsapp_no'       => $request['whatsapp_no'],
                'vimeo_link'        => $request['vimeo_link'],
                'from_email'        => $request['from_email'],
                'email_password'    => $request['email_password'],
               
            ];
            //---------
            if(!empty($companyProfile)){
               $companyProfile = CompanyProfile::find($companyProfile->id)->update($data);

            } else {
                $companyProfile = CompanyProfile::create($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            $companyProfile = null;
            DB::rollback();
        }
        //------------

        if ($companyProfile) {

            // Set notification
            $notification = [
                '_status'   => true,
                '_message'  => __('messages.profile_updated'),
                '_type'     => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.company-profile.index')->with(['notification' => $notification]);

        } else {
            // Set notification
            $notification = [
                '_status'   => false,
                '_message'  => __('messages.profile_updation_failed'),
                '_type'     => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.company-profile.index')->withInput()->with(['notification' => $notification]);
        }
    }
}
