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

        // Counts for tabs
        $totalAllCount = User::getUsers(null, null, null, ['user_type' => false]);
        $totalDemoCount = User::getUsers(null, null, null, ['user_type' => 'demo']);
        $totalOfflineCount = User::getUsers(null, null, null, ['user_type' => 'offline']);
        $totalOnlineCount = User::getUsers(null, null, null, ['user_type' => 'online']);

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->groupBy('coach_name')
            ->pluck('coach_name');

        // Meal and Product types
        $mealTypes = MealType::where('status', 1)->orderBy('name')->get();
        $productTypes = ProductType::where('status', 1)->orderBy('name')->get();

        // Titles and counts for current selected tab
        if ($userType == 'offline') {
            $currentTabTitle = 'Offline users';
            $currentTabSubtitle = 'Manage in-club members, coach assignments, plans and collections';
            $currentTabCount = $totalOfflineCount;
        } elseif ($userType == 'online') {
            $currentTabTitle = 'Online users';
            $currentTabSubtitle = 'Manage remote online members, coach assignments, and plans';
            $currentTabCount = $totalOnlineCount;
        } elseif ($userType == 'demo') {
            $currentTabTitle = 'Demo users';
            $currentTabSubtitle = 'Manage trial & demo members, coach assignments, and plans';
            $currentTabCount = $totalDemoCount;
        } else {
            $currentTabTitle = 'All users';
            $currentTabSubtitle = 'Manage all registered members, coach assignments, plans and collections';
            $currentTabCount = $totalAllCount;
        }

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['userType'] = $userType;
        $this->viewData['coachesList'] = $coachesList;
        $this->viewData['mealTypes'] = $mealTypes;
        $this->viewData['productTypes'] = $productTypes;
        $this->viewData['totalAllCount'] = $totalAllCount;
        $this->viewData['totalDemoCount'] = $totalDemoCount;
        $this->viewData['totalOfflineCount'] = $totalOfflineCount;
        $this->viewData['totalOnlineCount'] = $totalOnlineCount;
        $this->viewData['currentTabTitle'] = $currentTabTitle;
        $this->viewData['currentTabSubtitle'] = $currentTabSubtitle;
        $this->viewData['currentTabCount'] = $currentTabCount;
        
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
        $sort   = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'] ?? null;
        
        // Filter Parameters
        $filter = array(
            "name" => $request->name,
            "email" => $request->email,
            "mobile_number" => $request->mobile_number,
            "coach_name" => $request->coach_name,
            "plan_id" => $request->plan_id,
            "payment_status" => $request->payment_status,
            "date_range" => $request->date_range,
            'user_type' => $request['user_type']
        );

        // Getting Users Records
        $records_count  = User::getUsers(null, null, $search, $filter, $sort);
        $records        = User::getUsers($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            $colors = [
                ['bg' => '#eff6ff', 'color' => '#2563eb'], // Blue
                ['bg' => '#f3e8ff', 'color' => '#9333ea'], // Purple
                ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
                ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
                ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber
                ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
                ['bg' => '#e0e7ff', 'color' => '#4f46e5'], // Indigo
            ];

            foreach($records as $key => $value)
            {
                $name           = !empty($value->name) ? $value->name : 'N/A';
                $email          = !empty($value->email) ? $value->email : '';
                $mobile_number  = !empty($value->mobile_number) ? $value->mobile_number : 'N/A';
                $coach_name     = !empty($value->coach_name) ? $value->coach_name : 'N/A';
                $meal_type      = !empty($value->meal_type->name) ? $value->meal_type->name : '';
                $product_type   = !empty($value->product_type->name) ? $value->product_type->name : '';
                $due_amount     = !empty($value->due_amount) ? (float)$value->due_amount : 0;
                $days           = $value->days ?? 0;

                // 1. Initials and Avatar
                $initials = '';
                $nameWords = explode(' ', trim($name));
                foreach ($nameWords as $w) {
                    if (!empty($w)) {
                        $initials .= strtoupper(substr($w, 0, 1));
                    }
                }
                $initials = substr($initials, 0, 2);
                if (empty($initials)) $initials = 'U';

                $cIdx = abs(crc32($name)) % count($colors);
                $avatarCol = $colors[$cIdx];

                $profileImageUrl = null;
                if (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                    $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                } elseif (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                    $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                }

                if ($profileImageUrl) {
                    $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 30px; height: 30px; min-width: 30px; object-fit: cover;" alt="'.e($name).'" />';
                } else {
                    $avatarInner = '<div class="fcc-member-avatar" style="width: 30px; height: 30px; min-width: 30px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                }

                $memberHtml = '<div class="d-flex align-items-center gap-2">
                    <div class="fcc-avatar-wrapper" style="position: relative; width: 38px; height: 38px; min-width: 38px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <div class="fcc-avatar-ring" style="position: absolute; inset: 0; border-radius: 50%; border: 1.5px solid #cbd5e1; border-top-color: transparent; border-bottom-color: transparent; pointer-events: none;"></div>
                        '.$avatarInner.'
                    </div>
                    <div>
                        <div class="fcc-member-name fw-bold" style="color: #0f172a; font-size: 13.5px; line-height: 1.25;">'.e($name).'</div>
                        <div class="fcc-member-email text-muted" style="font-size: 11.5px; margin-top: 1px;">'.e($email).'</div>
                    </div>
                </div>';

                // 2. User Type
                $userState = !empty($value->user_state) ? $value->user_state : (($request->user_type == 'offline') ? 'Offline' : (($request->user_type == 'online') ? 'Online' : 'Offline'));
                $userTypeTitle = ($value->user_type == 'Demo User' || $value->user_type == '3 Days Trial') ? 'Demo' : 'Regular';
                $userTypeHtml = '<div>
                    <div style="font-size: 13px; font-weight: 500; color: #334155;">'.$userTypeTitle.'</div>
                    <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 6px; margin-top: 2px;">'.$userState.'</span>
                </div>';

                // 3. Contact
                $contactHtml = '<span style="font-size: 13px; color: #334155; font-weight: 500;">'.e($mobile_number).'</span>';

                // 4. Coach
                $coachHtml = '<span style="font-size: 13px; color: #334155; font-weight: 500;">'.e($coach_name).'</span>';

                // 5. Plan
                $planTitle = !empty($meal_type) ? $meal_type : (!empty($product_type) ? $product_type : 'Basic Plan');
                $tierBadge = !empty($product_type) ? $product_type : 'General';
                $tierStyle = match($tierBadge) {
                    'Silver' => ['bg' => '#e0e7ff', 'color' => '#3730a3'],
                    'Gold' => ['bg' => '#fef3c7', 'color' => '#b45309'],
                    'Bronze' => ['bg' => '#ffedd5', 'color' => '#c2410c'],
                    default => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                };
                $planHtml = '<div>
                    <div style="font-size: 13px; color: #334155; font-weight: 500;">'.e($planTitle).'</div>
                    <span class="badge" style="background: '.$tierStyle['bg'].'; color: '.$tierStyle['color'].'; font-size: 10.5px; font-weight: 600; padding: 2px 8px; border-radius: 6px; margin-top: 2px;">'.e($tierBadge).'</span>
                </div>';

                // 6. Renewal
                $daysLeft = (int)$days;
                if ($daysLeft <= 3 && $daysLeft >= 0) {
                    $renewalHtml = '<span class="badge" style="background: #fef3c7; color: #d97706; font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">'.$daysLeft.' days</span>';
                } elseif ($daysLeft < 0) {
                    $renewalHtml = '<span class="badge" style="background: #fee2e2; color: #ef4444; font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">Expired</span>';
                } else {
                    $renewalHtml = '<span style="font-size: 13px; color: #334155; font-weight: 500;">'.$daysLeft.' days</span>';
                }

                // 7. Due Amount
                $dueHtml = '<span style="font-size: 13px; color: #334155; font-weight: 500;">₹'.number_format($dueAmount = abs($due_amount), 0).'</span>';

                // 8. Status
                if ($value->status == 0) {
                    $statusHtml = '<span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">Inactive</span>';
                } else {
                    $statusHtml = '<span class="badge" style="background: #dcfce7; color: #166534; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">Active</span>';
                }

                // 9. Actions
                $action = '<div class="dropdown custom-dropdown d-inline-block">
                    <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_'.$value->id.'" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #2563eb; font-size: 16px; text-decoration: none; padding: 4px 8px; cursor: pointer;">
                        <i class="fa fa-ellipsis-h" style="font-size: 17px; letter-spacing: 2px;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$value->id.'" style="border-radius: 12px; min-width: 195px; padding: 6px; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif;">
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.users.edit', ['id' => ev($value->id)]).'"><i class="fa fa-pencil me-2 text-muted"></i> Edit</a>
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.users.viewWeights', ['id' => ev($value->id)]).'"><i class="fa fa-balance-scale me-2 text-muted"></i> View Weight</a>
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.users.viewAttendance', ['id' => ev($value->id)]).'"><i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance</a>
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.manual-attendances.manual-attendance', ['id' => ev($value->id)]).'"><i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance</a>
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.track-shake.index', ['id' => ev($value->id)]).'"><i class="fa fa-coffee me-2 text-muted"></i> Track Shake</a>
                        <a class="dropdown-item py-2 px-3 rounded-2 edit-user-quick cursor-pointer" data-url="' . route('nutritionPanel.users.editUserQuick', ['id' => ev($value->id)]) . '"><i class="fa fa-bolt me-2 text-muted"></i> Edit User Quick</a>
                        <a class="dropdown-item py-2 px-3 rounded-2 add-user-days cursor-pointer" data-url="' . route('nutritionPanel.users.addUserDays', ['id' => ev($value->id)]) . '"><i class="fa fa-plus-circle me-2 text-muted"></i> Add User Days</a>
                        <a class="dropdown-item py-2 px-3 rounded-2 subtract-user-days cursor-pointer" data-url="' . route('nutritionPanel.users.subtractUserDays', ['id' => ev($value->id)]) . '"><i class="fa fa-minus-circle me-2 text-muted"></i> Subtract User Days</a>
                        <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.orders.index', ['id' => ev($value->id)]).'"><i class="fa fa-shopping-cart me-2 text-muted"></i> Purchase Products</a>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item py-2 px-3 rounded-2 text-primary fw-bold" href="'.route('nutritionPanel.users.details', ['id' => ev($value->id)]).'"><i class="fa fa-id-card-o me-2 text-primary"></i> View Details</a>
                    </div>
                </div>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "member"            => $memberHtml,
                    "user_type"         => $userTypeHtml,
                    "contact"           => $contactHtml,
                    "coach_name"        => $coachHtml,
                    "plan"              => $planHtml,
                    "days"              => $renewalHtml,
                    "due_amount"        => $dueHtml,
                    "status"            => $statusHtml,
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
    public function create(Request $request)
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

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->select('coach_name', DB::raw('COUNT(id) as total_members'))
            ->groupBy('coach_name')
            ->orderByDesc('total_members')
            ->get();

        if ($coachesList->isEmpty() && !empty($authUser->name)) {
            $coachesList = collect([
                (object)[
                    'coach_name' => $authUser->name,
                    'total_members' => 0
                ]
            ]);
        }

        $selectedUserType = $request->get('user_type');
        if (!$selectedUserType) {
            if ($request->get('type') === 'demo') {
                $selectedUserType = 'Demo User';
            } elseif ($request->get('type') === 'ums' || $request->get('type') === 'regular') {
                $selectedUserType = 'Regular User';
            }
        }

        // Dynamically route to dedicated Demo page if demo requested
        if ($request->get('type') === 'demo' || $selectedUserType === 'Demo User') {
            return $this->createDemo($request);
        }

        // View Data
        $this->viewData['breadcrumb']       = $breadcrumb;
        $this->viewData['mealTypes']        = $mealTypes;
        $this->viewData['productTypes']     = $productTypes;
        $this->viewData['coachesList']      = $coachesList;
        $this->viewData['selectedUserType'] = $selectedUserType;

        return view('nutrition-panel.users.create')->with($this->viewData);
    }

    /**
     * View create Demo User (Dedicated Image 1 design).
     */
    public function createDemo(Request $request)
    {
        $authUser = auth()->user();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Demo Users' => route('nutritionPanel.users.index') . '/demo',
            'Add Demo User' => '',
        ];

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->select('coach_name', DB::raw('COUNT(id) as total_members'))
            ->groupBy('coach_name')
            ->orderByDesc('total_members')
            ->get();

        if ($coachesList->isEmpty() && !empty($authUser->name)) {
            $coachesList = collect([
                (object)[
                    'coach_name' => $authUser->name,
                    'total_members' => 0
                ]
            ]);
        }

        $this->viewData['breadcrumb']   = $breadcrumb;
        $this->viewData['authUser']     = $authUser;
        $this->viewData['coachesList']  = $coachesList;

        return view('nutrition-panel.users.create-demo')->with($this->viewData);
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
        
        $isDemo = $request->boolean('is_demo') || $request->input('user_type') === 'Demo User';
        $userType = $isDemo ? 'Demo User' : ($request->input('user_type') ?: 'Regular User');

        if ($isDemo) {
            $days = (int)$request->input('days', 3);
        } elseif ($userType === '3 Days Trial') {
            $days = 3;
        } else {
            $days = (int)$request->input('days', 0);
        }

        $startDate = !empty($request['start_date']) ? date('Y-m-d', strtotime($request['start_date'])) : Carbon::now()->toDateString();
        $endDate = Carbon::parse($startDate)->addDays($days)->toDateString();
        $dob = !empty($request['date_of_birth']) ? date('Y-m-d', strtotime($request['date_of_birth'])) : null;
        $weight = $request['weight'] ?? $request['current_weight'] ?? null;
        $weightGoal = $request['weight_goal'] ?? $request['goal_weight'] ?? null;
        $password = !empty($request['new_pass']) ? bcrypt($request['new_pass']) : (!empty($request['password']) ? bcrypt($request['password']) : bcrypt(Str::random(10)));

        // Begin Transaction
        DB::beginTransaction();

        // Create User
        try {

            // Set data
            $data = [
                'name'                      => $request['name'] ?? $request['user_name'],
                'email'                     => $request['email'],
                'email_verified_at'         => Carbon::now()->toDateTimeString(),
                'country_code'              => $request['country_code'] ?? '+91',
                'mobile_number'             => $request['mobile_number'],
                'mobile_number_verified_at' => Carbon::now()->toDateTimeString(),
                'date_of_birth'             => $dob,
                'user_type'                 => $userType,
                'user_state'                => $request['user_state'] ?? 'Offline',
                'coach_name'                => $request['coach_name'],
                'meal_type_id'              => $request['meal_type_id'] ?? null,
                'product_type_id'           => $request['product_type_id'] ?? 1,
                'starting_weight'           => $weight,
                'current_weight'            => $weight,
                'start_date'                => $startDate,
                'end_date'                  => $endDate,
                'days'                      => $days,
                'age'                       => $request['age'] ?? null,
                'height'                    => $request['height'] ?? null,
                'gender'                    => $request['gender'] ?? null,
                'weight_goal'               => $weightGoal,
                'password'                  => $password,
                'role_id'                   => 3,
                'role_type'                 => 'user',
                'created_by'                => $authUser->id,
                'created_at'                => Carbon::now()->toDateTimeString(),
                'updated_at'                => Carbon::now()->toDateTimeString()
            ];

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
            \Log::error('Nutrition User create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------
        if (!is_null($user)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => $isDemo ? 'Demo User' : 'User']),
                '_type' => 'success',
            ];
            //-----------------

            $redirectRoute = $isDemo ? route('nutritionPanel.users.index') . '/demo' : route('nutritionPanel.users.index');
            return redirect($redirectRoute)->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => $errorMessage ?: __('messages.record_creation_failed', ['record' => 'User']),
                '_type' => 'error',
            ];
            //-----------------

            $failRoute = $isDemo ? route('nutritionPanel.users.createDemo') : route('nutritionPanel.users.create');
            return redirect($failRoute)->withInput()->with(['notification' => $notification]);
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
        $authUser = auth()->user();
        $user = User::where('id', dv($id))->first();

        if (!$user) {
            return redirect()->route('nutritionPanel.users.index');
        }

        $mealTypes = MealType::where('status', 1)->orderBy('id', 'DESC')->get();
        $productTypes = ProductType::where('status', 1)->orderBy('id', 'DESC')->get();

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->select('coach_name', DB::raw('COUNT(id) as total_members'))
            ->groupBy('coach_name')
            ->orderByDesc('total_members')
            ->get();

        if ($coachesList->isEmpty() && !empty($authUser->name)) {
            $coachesList = collect([
                (object)[
                    'coach_name' => $authUser->name,
                    'total_members' => 0
                ]
            ]);
        }
        
        // Send view data
        unset($this->viewData['breadcrumb']);
        $this->viewData['authUser']     = $authUser;
        $this->viewData['user']         = $user;
        $this->viewData['mealTypes']    = $mealTypes;
        $this->viewData['productTypes'] = $productTypes;
        $this->viewData['coachesList']  = $coachesList;

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
            \Log::error('Nutrition User update Error: ' . $e->getMessage());
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
            \Log::error('Nutrition User update Error: ' . $e->getMessage());
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
            \Log::error('Nutrition User push Error: ' . $e->getMessage());
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
            \Log::error('Nutrition User push2 Error: ' . $e->getMessage());
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

        if (!$user) {
            return redirect()->route('nutritionPanel.users.index');
        }

        $firstRecord = Attendance::select('attendances.id as attendance_id', 'attendances.user_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('attendances.user_id', $user->id)
            ->where('type', 2)
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->orderBy('attendances.date', 'ASC')
            ->orderBy('attendances.id', 'ASC')
            ->first();

        $lastRecord = Attendance::select('attendances.id as attendance_id', 'attendances.user_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('attendances.user_id', $user->id)
            ->where('type', 2)
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->orderBy('attendances.date', 'DESC')
            ->orderBy('attendances.id', 'DESC')
            ->first();

        $secondLastRecord = Attendance::select('attendances.id as attendance_id', 'attendances.user_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('attendances.user_id', $user->id)
            ->where('type', 2)
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->orderBy('attendances.date', 'DESC')
            ->orderBy('attendances.id', 'DESC')
            ->skip(1)
            ->first();

        $weights = Attendance::select('weight', 'date')
            ->where('user_id', $user->id)
            ->where('type', 2)
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        // If user has only 1 attendance weight and starting_weight exists, include starting weight baseline
        if ($weights->count() === 1 && !empty($user->starting_weight) && (float)$user->starting_weight > 0) {
            $joinDate = !empty($user->created_at) ? date('Y-m-d', strtotime($user->created_at)) : date('Y-m-d', strtotime('-7 days'));
            if ($joinDate < $weights->first()->date) {
                $startingEntry = (object)[
                    'weight' => (float)$user->starting_weight,
                    'date'   => $joinDate
                ];
                $weights->prepend($startingEntry);
            }
        }

        // View Data
        $chartStart = !empty($weights->first()) ? date('d M', strtotime($weights->first()->date)) : date('d M', strtotime('-30 days'));
        $chartEnd = !empty($weights->last()) ? date('d M Y', strtotime($weights->last()->date)) : date('d M Y');
        $chartDateRangeText = $chartStart . ' – ' . $chartEnd;

        $weightDatesFormatted = $weights->map(function($w) { 
            return date('d M', strtotime($w->date)); 
        })->values()->toArray();

        $weightValuesFormatted = $weights->map(function($w) { 
            return round((float)$w->weight, 1); 
        })->values()->toArray();

        $minRecord = $weights->count() > 0 ? $weights->sortBy('weight')->first() : null;
        $maxRecord = $weights->count() > 0 ? $weights->sortByDesc('weight')->first() : null;
        $startWeight = !empty($firstRecord->weight) ? (float)$firstRecord->weight : (!empty($user->starting_weight) ? (float)$user->starting_weight : 0);
        $currentWeight = !empty($lastRecord->weight) ? (float)$lastRecord->weight : (!empty($user->current_weight) ? (float)$user->current_weight : 0);
        $netChange = ($startWeight > 0 && $currentWeight > 0) ? round($currentWeight - $startWeight, 1) : 0;

        $chartData = $weights->map(function($w) {
            return [
                'date' => date('d M Y', strtotime($w->date)),
                'short_date' => date('d M', strtotime($w->date)),
                'weight' => round((float)$w->weight, 1),
                'timestamp' => strtotime($w->date) * 1000
            ];
        })->values()->toArray();

        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['firstRecord'] = $firstRecord;
        $this->viewData['lastRecord'] = $lastRecord;
        $this->viewData['secondLastRecord'] = $secondLastRecord;
        $this->viewData['minRecord'] = $minRecord;
        $this->viewData['maxRecord'] = $maxRecord;
        $this->viewData['startWeight'] = $startWeight;
        $this->viewData['currentWeight'] = $currentWeight;
        $this->viewData['netChange'] = $netChange;
        $this->viewData['chartData'] = $chartData;
        $this->viewData['user'] = $user;
        $this->viewData['weights'] = $weights;
        $this->viewData['weightDates'] = $weights->pluck('date')->toArray();
        $this->viewData['weightDatesFormatted'] = $weightDatesFormatted;
        $this->viewData['weightValues'] = $weightValuesFormatted;
        $this->viewData['chartDateRangeText'] = $chartDateRangeText;
        
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
        $start  = $request->get('start') ? intval($request->get('start')) : 0;
        $limit  = $request->get('length') ? intval($request->get('length')) : 20;
        $sort   = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'] ?? null;
        
        // Filter Parameters
        $filter = array(
            "user_id" => $request->user_id,
            "year" => $request->year,
            "date_range" => $request->date_range,
        );

        $arr_data = array();
        $totalRecords = 0;

        try {
            // Getting Weights Records
            $records_count  = Attendance::getViewWeights(null, null, $search, $filter, $sort);
            $records        = Attendance::getViewWeights($limit, $start, $search, $filter, $sort);
            $totalRecords   = intval($records_count);

            if(!empty($records) && count($records) > 0)
            {
                foreach($records as $key => $value)
                {
                    $entryNo    = $start + $key + 1;
                    $name       = !empty($value->name) ? $value->name : 'N/A';
                    $weightVal  = !empty($value->weight) ? number_format((float)$value->weight, 1) . ' kg' : 'N/A';
                    $dateStr    = !empty($value->date) ? date('d M Y', strtotime($value->date)) : 'N/A';
                    $hasImage   = !empty($value->weight_image);
                    $encryptedId = ev($value->id);

                    // Evidence Column
                    if ($hasImage) {
                        $evidenceHtml = '<a href="javascript:;" data-url="' . route('nutritionPanel.users.viewWeightImage', ['id' => $encryptedId]) . '" class="btn fcc-btn-view-image view-image"><i class="fa fa-picture-o me-1"></i> View image</a>';
                    } else {
                        $evidenceHtml = '<span class="fcc-evidence-badge none"><span class="fcc-dot dot-gray"></span> No image</span>';
                    }

                    // Action Column
                    $userId = $value->user_id ?? 0;
                    $userEncryptedId = $userId ? ev($userId) : '';
                    $actionHtml = '<div class="d-flex align-items-center justify-content-end">
                        <div class="dropdown custom-dropdown d-inline-block">
                            <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_'.$value->id.'" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$value->id.'" style="border-radius: 12px; min-width: 180px; padding: 6px; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif;">
                                '.($hasImage ? '<a class="dropdown-item py-2 px-3 rounded-2 view-image" href="javascript:;" data-url="' . route('nutritionPanel.users.viewWeightImage', ['id' => $encryptedId]) . '"><i class="fa fa-picture-o me-2 text-muted"></i> View Photo</a>' : '').'
                                <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userEncryptedId ? route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) : 'javascript:;').'"><i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance</a>
                                <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userEncryptedId ? route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) : 'javascript:;').'"><i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance</a>
                                <div class="dropdown-divider my-1"></div>
                                <a class="dropdown-item py-2 px-3 rounded-2 text-primary fw-bold" href="'.($userEncryptedId ? route('nutritionPanel.users.details', ['id' => $userEncryptedId]) : 'javascript:;').'"><i class="fa fa-id-card-o me-2 text-primary"></i> View Details</a>
                            </div>
                        </div>
                    </div>';

                    // Array Data
                    $arr_data[] = array(
                        "id"                => $value->id,
                        "entry"             => $entryNo,
                        "name"              => $name,
                        "date"              => $dateStr,
                        "weight"            => $weightVal,
                        "evidence"          => $evidenceHtml,
                        "action"            => $actionHtml,
                        "weight_image"      => $hasImage ? $evidenceHtml : 'N/A'
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('getViewWeights error: ' . $e->getMessage());
        }

        $response = array(
            "draw"                  => intval($draw),
            "recordsTotal"          => $totalRecords,
            "recordsFiltered"       => $totalRecords,
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return response()->json($response);
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

        $breadcrumbButton = [];

        $user = User::where('id', dv($id))->first();

        if (!$user) {
            return redirect()->route('nutritionPanel.users.index');
        }

        $year = $request->year ?? date('Y'); 

        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear();

        $attendances = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.type', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where("users.role_type", 'user')
            ->where('type', 2)
            ->where('user_id', $user->id)
            ->whereBetween('attendances.date', [$startDate, $endDate])
            ->orderBy('attendances.date','ASC')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // 1. Total Check-ins
        $totalCheckIns = $attendances->count();

        // 2. Last Check-in
        $lastAttendance = $attendances->last();
        $lastCheckInDate = $lastAttendance ? date('d M Y', strtotime($lastAttendance->date)) : null;

        // 3. Recent Check-ins (last 6 dates)
        $recentCheckIns = $attendances->take(-8)->map(function($a) {
            return (object)[
                'date' => $a->date,
                'formatted' => date('d M', strtotime($a->date)),
                'full' => date('d M Y', strtotime($a->date)),
                'weight' => $a->weight
            ];
        })->values();

        // 4. Calculate Longest Streak
        $longestStreakDays = 0;
        $longestStreakRange = '';
        $currentStreakDays = 0;
        $currentStreakStart = null;
        $longestStreakStart = null;
        $longestStreakEnd = null;
        $prevDate = null;

        foreach ($attendances as $att) {
            $attDate = Carbon::parse($att->date);
            if ($prevDate && $prevDate->copy()->addDay()->isSameDay($attDate)) {
                $currentStreakDays++;
            } else {
                $currentStreakDays = 1;
                $currentStreakStart = $attDate;
            }

            if ($currentStreakDays >= $longestStreakDays) {
                $longestStreakDays = $currentStreakDays;
                $longestStreakStart = $currentStreakStart;
                $longestStreakEnd = $attDate;
            }

            $prevDate = $attDate;
        }

        if ($longestStreakDays > 0 && $longestStreakStart && $longestStreakEnd) {
            if ($longestStreakStart->isSameDay($longestStreakEnd)) {
                $longestStreakRange = $longestStreakStart->format('d M');
            } elseif ($longestStreakStart->month === $longestStreakEnd->month) {
                $longestStreakRange = $longestStreakStart->format('d') . '–' . $longestStreakEnd->format('d M');
            } else {
                $longestStreakRange = $longestStreakStart->format('d M') . ' – ' . $longestStreakEnd->format('d M');
            }
        }

        // View Data
        $this->viewData['breadcrumbFilter']     = $breadcrumb;
        $this->viewData['breadcrumbButton']     = $breadcrumbButton;
        $this->viewData['authUser']             = $authUser;
        $this->viewData['user']                 = $user;
        $this->viewData['year']                 = $year;
        $this->viewData['attendances']          = $attendances;
        $this->viewData['totalCheckIns']        = $totalCheckIns;
        $this->viewData['longestStreakDays']    = $longestStreakDays;
        $this->viewData['longestStreakRange']   = $longestStreakRange;
        $this->viewData['lastCheckInDate']      = $lastCheckInDate;
        $this->viewData['recentCheckIns']       = $recentCheckIns;
        
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

        if (!$user) {
            return redirect()->route('nutritionPanel.users.index');
        }
 
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Users' => route('nutritionPanel.users.index'),
            'User Details' => '',
        ];

        $lastRecord = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->where('type', 2)
            ->where('user_id', $user->id)
            ->orderBy('attendances.date', 'DESC')
            ->orderBy('attendances.id', 'DESC')
            ->first();

        $maxWeight = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->where('type', 2)
            ->where('user_id', $user->id)
            ->orderBy('attendances.weight', 'DESC')
            ->first();

        $minWeight = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->where('type', 2)
            ->where('user_id', $user->id)
            ->orderBy('attendances.weight', 'ASC')
            ->first();

        $latestWeight = (float)($lastRecord->weight ?? ($user->current_weight ?? 0));
        $startingWeight = (float)($user->starting_weight ?? 0);
        $weightDiff = ($latestWeight > 0 && $startingWeight > 0) ? round($latestWeight - $startingWeight, 2) : 0;
        
        // Send view data
        unset($this->viewData['breadcrumb']);
        $this->viewData['user'] = $user;
        $this->viewData['lastRecord'] = $lastRecord;
        $this->viewData['maxWeight'] = $maxWeight;
        $this->viewData['minWeight'] = $minWeight;
        $this->viewData['latestWeight'] = $latestWeight;
        $this->viewData['startingWeight'] = $startingWeight;
        $this->viewData['weightDiff'] = $weightDiff;
        
        return view('nutrition-panel.users.details')->with($this->viewData);
    }
}