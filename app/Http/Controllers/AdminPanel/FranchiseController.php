<?php
   
namespace App\Http\Controllers\AdminPanel;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\FranchiseProduct;
use App\Models\FranchiseMembershipPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\UploadImage;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
   
class FranchiseController extends Controller
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
     * View Franchises list.
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
            'Franchises' => '',
        ];

        // Filter Button
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'filter',
            'btn_text' => __('language.filter'),
            'attributes' => []
        ];

        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.franchises.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        return view('admin-panel.franchises.index')->with($this->viewData);
    }

    public function getFranchises(Request $request){
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
            "mobile_number" => $request->mobile_number,
            "date_range" => $request->date_range,
        );
        // Getting Franchise Records
        $records_count = User::GetFranchises(null, null, $search, $filter, $sort);
        $records = User::GetFranchises($limit, $start, $search, $filter, $sort);
        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name = 'N/A';
                $registration = 'N/A';
                $mobile_number = 'N/A';
                $email = '';
                $pending_amount = 0;
                $pending_days = 0;
                $total_membership = 0;
                $status = '';

                $total_membership = FranchiseMembershipPlan::where('franchise_id', $value->id)->count();
                $pending_amount = FranchiseMembershipPlan::where('franchise_id', $value->id)->where('payment_status', 1)->sum('pending_amount');

                if($pending_amount > 0){
                    $pending_amount = '<span class="text-danger">'.$pending_amount.'</span>';
                } else {
                    $pending_amount = '<span class="text-success">'.$pending_amount.'</span>';
                }

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
                    $email = $value->email;
                }

                if (!empty($value->end_date)) {
                    $endDate = Carbon::parse($value->end_date)->startOfDay();
                    $today   = Carbon::today();

                    // 🔹 Difference without negative value
                    $pending_days = $today->diffInDays($endDate, false);
                }

                if($pending_days > 0){
                    $pending_days = '<span class="text-success">'.$pending_days.'</span>';
                } else {
                    $pending_days = '<span class="text-danger">'.$pending_days.'</span>';
                }

                if(!empty($value->mobile_number))
                {
                    $mobile_number = $value->mobile_number;
                }

                if ( $value->status == 0 )
                {
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } 
                else 
                {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('adminPanel.franchises.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                $view_membership = '<a href="' . route('adminPanel.franchise-membership-plans.index', ['id' => ev($value->id)]) . '" class="" title="View Memberships"><div class="badge badge-primary">View Memberships ('.$total_membership.')</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"            => $value->id,
                    "name"          => $name,
                    "email"         => $email,
                    "mobile_number" => $mobile_number,
                    'pending_amount' => $pending_amount,
                    'pending_days'   => $pending_days,
                    "view_membership"  => $view_membership,
                    "status"        => $status,
                    "action"        => $action,
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
        * View create Franchises.
        *
        * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
        *
        * @author Sandeep
        * @created 20 Jan 2023
    */
    public function create()
    {
        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Franchises' => route('adminPanel.franchises.index'),
            __('language.create') => '',
        ];

        $products = Product::where('status' , 1)->get();

        // View Data
        $this->viewData['breadcrumb']   = $breadcrumb;
        $this->viewData['products']     = $products;

        return view('admin-panel.franchises.create')->with($this->viewData);
    }

    /**
     * Store Franchises.
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
        
        $franchise      = null;
        $errorMessage   = null;
        
        // Begin Transaction
        DB::beginTransaction();
        
        // Create Franchise
        try {

            // Set data
            $data = [
                'name'                      => $request['name'],
                'email'                     => $request['email'],
                'email_verified_at'         => Carbon::now()->toDateTimeString(),
                'mobile_number'             => $request['mobile_number'],
                'mobile_number_verified_at' => Carbon::now()->toDateTimeString(),
                'role_id'                   => 2,
                'role_type'                 => 'franchise',
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
            
            $franchise = User::create($data);

            // Add Franchise Product Data
            if(isset($request['products']) && count($request['products']) > 0)
            {
                foreach($request['products'] as $key => $value)
                {
                    $franchiseData = [
                        'franchise_id'      => $franchise->id,
                        'product_id'        => $value,
                    ];

                    FranchiseProduct::create($franchiseData);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            $franchise      = null;
            $errorMessage   = $e->getMessage();
            DB::rollback();
        }
        //------------
        if (!is_null($franchise)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Franchise']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchises.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Franchise']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchise.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Franchise.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 21 Feb 2023
     */
    public function edit(Request $request, $id)
    {
        $franchise = User::where('id', dv($id))->first();
        
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Franchises' => route('adminPanel.franchises.index'),
            'Edit Franchise' => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['franchise'] = $franchise;
        
        return view('admin-panel.franchises.edit')->with($this->viewData);
    }

    /**
     * Update Franchise.
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

        $franchiseUpdate    = false;
        $errorMessage       = null;
        
        // Update language
        DB::beginTransaction();

        try {

            // Update Franchise
            $franchise = User::where('id', dv($id))->first();

            $data = [
                'name'          => $request['name'],
                'email'         => $request['email'],
                'mobile_number' => $request['mobile_number'],
                'updated_at'    => Carbon::now()->toDateTimeString()
            ];

            if(!empty($request['new_pass'])){
                $data['password'] = bcrypt($request['new_pass']);
            }

            // Upload Franchise image
            if ($request->hasFile('image'))
            {
                // Remove old image
                if (!is_null($franchise->image)) {
                    delete_image(config('constants.users.image_path'), $franchise->image);
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
            $franchiseUpdate = User::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $franchiseUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }
        //------------

        if (!is_null($franchiseUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Franchise']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchises.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Franchise']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.franchises.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $franchise = User::whereIn('id', $ids)->delete();
        
        // Set response
        if ($franchise == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Franchise']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Franchise']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
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
            $franchise = User::where('mobile_number', $request['mobile_number'])->where('role_type', 'franchise')->first();

            if (!is_null($franchise)) {
                if ($request->filled('id') && $franchise->id == $request['id']) {
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
            $franchise = User::where('email', $request['email'])->where('role_type', 'franchise')->first();

            if (!is_null($franchise)) {
                if ($request->filled('id') && $franchise->id == $request['id']) {
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