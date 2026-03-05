<?php
   
namespace App\Http\Controllers\AdminPanel;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\UploadImage;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
   
class UserController extends Controller
{
    use UploadImage, UploadFile;

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
     * View Videos list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyasnh
     * @created_at 19 Jan 2023
     */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Users' => '',
        ];
        // Filter Button
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'filter',
            'btn_text' => __('language.filter'),
            'attributes' => []
        ];
        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        return view('admin-panel.users.index')->with($this->viewData);
    }

    public function getUsers(Request $request){
        $authUser = auth()->user();
        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "name" => $request->name,
            "email" => $request->email,
            "m_no" => $request->m_no,
            "date_range" => $request->date_range,
            "filter_platform" => $request->filter_platform,
        );
        // Getting User Records
        $records_count = User::GetUsers(null, null, $search, $filter, $sort);
        $records = User::GetUsers($limit, $start, $search, $filter, $sort);
        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name = 'N/A';
                $registration = 'N/A';
                $m_no = 'N/A';
                $email= '';
                $status = '';

                // Preparing Data
                if(!empty($value->name))
                {
                    $name = $value->name;
                }
                if(!empty($value->created_at))
                {
                    $registration = date("d-m-Y / h:i A",strtotime('+5.5 hours', strtotime($value->created_at)));
                }

                if(!empty($value->email))
                {   
                    if(!empty($value->email_verified_at)){
                        $email = '<div>'.$value->email.'<span style="color:green;"> &#10004; </span></div>';
                    }else{
                        $email = '<div>'.$value->email.'<span style="color:red;"> &#10006; </span></div>';
                    }
                }

                if(!empty($value->mobile_number))
                {
                    if(!empty($value->country_code)){
                        $m_no = '<div>'.$value->country_code.'-'.$value->mobile_number.'<span style="color:green;"> &#10004; </span></div>';
                    } else {
                        $m_no = '<div>'.$value->mobile_number.'<span style="color:green;"> &#10004; </span></div>';
                    }
                }

                if($value->platform == config('constants.platforms.ANDROID.value'))
                {
                    $platform = '<label class="badge badge-primary">'.config('constants.platforms.ANDROID.value').'</label>';
                }
                else if($value->platform == config('constants.platforms.IOS.value'))
                {
                    $platform = '<label class="badge badge-warning">'.config('constants.platforms.IOS.value').'</label>';
                }
                else if($value->platform == config('constants.platforms.WEB.value'))
                {
                    $platform = '<label class="badge badge-info">'.config('constants.platforms.WEB.value').'</label>';
                }
                else{
                    $platform = '<label class="badge badge-dark">'.config('constants.platforms.ADMIN.value').'</label>';
                }

                if ( $value->status == 0 )
                {
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } 
                else 
                {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.users.details', ['id' => ev($value->id)]) . '" class="" title="View Details"><div class="badge badge-primary">View Details</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id" => $value->id,
                    "name"  => $name,
                    "email"   => $email,
                    "m_no" => $m_no,
                    "registration" => $registration,
                    "platform" => $platform,
                    "status" => $status,
                    "action" => $action,
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
     * Edit User.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function edit(Request $request, $id)
    {
        $user = User::where('id', dv($id))->first();
        
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Users' => route('adminPanel.users.index'),
            'User Profile' => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['user'] = $user;
        
        return view('admin-panel.users.edit')->with($this->viewData);
    }

    /**
     * Update User.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function update(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $userUpdate = false;
        $errorMessage = null;
        
        // Update language
        DB::beginTransaction();

        try {

            // Update User
            $user = User::where('id', dv($id))->first();

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile_number' => $request['mobile_number'],
                'created_by' => $authUser->id,
                'updated_by' => $authUser->id,
                'updated_at' => Carbon::now()->toDateTimeString()
            ];

            if(!empty($request['email_verify'])){
                $data['email_verified_at'] = Carbon::now()->toDateTimeString();
            }else{
                $data['email_verified_at'] = null;
            }

            if(!empty($request['new_pass'])){
                $data['password'] = bcrypt($request['new_pass']);
            }

            // Upload user image
            if ($request->hasFile('profile_image'))
            {
                // Remove old image
                if (!is_null($user->profile_image)) {
                    delete_image(config('constants.users.image_path'), $user->profile_image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('profile_image'), config('constants.users.image_path'), null, 'user-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['profile_image'] = config('constants.users.image_path').$imageName;
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

            return redirect()->route('adminPanel.users.index')->with(['notification' => $notification]);
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

            return redirect()->route('adminPanel.users.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Change status.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 01 Feb 2023
     */
    public function changeStatus(Request $request)
    {
        $language = User::toggleStatus($request['ids']);
        
        DB::table('oauth_access_tokens')->whereIn('user_id',$request['ids'])->delete();
        
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
     * @created_at 01 Feb 2023
     */
    public function destroy(Request $request)
    {
        $ids = $request['ids'];
        $video = User::whereIn('id', $ids)->delete();
        
        // Set response
        if ($video == true) 
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
     * Check user email.
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
            $user = User::where('email', $request['email'])->first();

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
     * Details User.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function getDetails(Request $request, $id)
    {
        $user = User::where('id', dv($id))->first();
 
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Users' => route('adminPanel.users.index'),
            'User Profile' => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['user'] = $user;
        
        return view('admin-panel.users.details')->with($this->viewData);
    }

    /**
     * Check user mobile.
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
            $user = User::where('mobile_number', $request['mobile_number'])->first();

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

}