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
                if (!empty($value->profile_image)) {
                    if (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                    } elseif (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    } else {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    }
                }

                if ($profileImageUrl) {
                    $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 24px; height: 24px; min-width: 24px; object-fit: cover;" alt="'.e($name).'" />';
                } else {
                    $avatarInner = '<div class="fcc-member-avatar" style="width: 24px; height: 24px; min-width: 24px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 10px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                }

                $memberHtml = '<div class="d-flex align-items-center gap-2" style="gap: 7px !important;">
                    <div class="fcc-avatar-wrapper" style="position: relative; width: 30px; height: 30px; min-width: 30px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <div class="fcc-avatar-ring" style="position: absolute; inset: 0; border-radius: 50%; border: 1.5px solid #cbd5e1; border-top-color: transparent; border-bottom-color: transparent; pointer-events: none;"></div>
                        '.$avatarInner.'
                    </div>
                    <div style="line-height: 1.15;">
                        <div class="fcc-member-name fw-bold" style="color: #0f172a; font-size: 12.5px; line-height: 1.2;">'.e($name).'</div>
                        <div class="fcc-member-email text-muted" style="font-size: 11px; margin-top: 0px; line-height: 1.1;">'.e($email).'</div>
                    </div>
                </div>';

                // 2. User Type
                $userState = !empty($value->user_state) ? $value->user_state : (($request->user_type == 'offline') ? 'Offline' : (($request->user_type == 'online') ? 'Online' : 'Offline'));
                $userTypeTitle = ($value->user_type == 'Demo User' || $value->user_type == '3 Days Trial') ? 'Demo' : 'Regular';
                $userTypeHtml = '<div style="line-height: 1.15;">
                    <div style="font-size: 12px; font-weight: 600; color: #334155; line-height: 1.2;">'.$userTypeTitle.'</div>
                    <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 9.5px; font-weight: 600; padding: 1px 6px; border-radius: 4px; margin-top: 1px; display: inline-block; line-height: 1.2;">'.$userState.'</span>
                </div>';

                // 3. Contact
                $contactHtml = '<span style="font-size: 12px; color: #334155; font-weight: 600; letter-spacing: 0.2px;">'.e($mobile_number).'</span>';

                // 4. Coach
                $coachHtml = '<span style="font-size: 12.5px; color: #334155; font-weight: 500;">'.e($coach_name).'</span>';

                // 5. Plan
                $planTitle = !empty($meal_type) ? $meal_type : (!empty($product_type) ? $product_type : 'Basic Plan');
                $tierBadge = !empty($product_type) ? $product_type : 'General';
                $tierStyle = match($tierBadge) {
                    'Silver' => ['bg' => '#e0e7ff', 'color' => '#3730a3'],
                    'Gold' => ['bg' => '#fef3c7', 'color' => '#b45309'],
                    'Bronze' => ['bg' => '#ffedd5', 'color' => '#c2410c'],
                    default => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                };
                $planHtml = '<div style="line-height: 1.15;">
                    <div style="font-size: 12px; color: #1e293b; font-weight: 600; line-height: 1.2;">'.e($planTitle).'</div>
                    <span class="badge" style="background: '.$tierStyle['bg'].'; color: '.$tierStyle['color'].'; font-size: 9.5px; font-weight: 600; padding: 1px 6px; border-radius: 4px; margin-top: 1px; display: inline-block; line-height: 1.2;">'.e($tierBadge).'</span>
                </div>';

                // 6. Renewal
                $daysLeft = (int)$days;
                if ($daysLeft <= 0) {
                    $renewalHtml = '<span class="badge" style="background: #fee2e2; color: #ef4444; font-size: 11px; font-weight: 600; padding: 1.5px 6px; border-radius: 4px; line-height: 1.2;">Expired</span>';
                } elseif ($daysLeft <= 3) {
                    $renewalHtml = '<span class="badge" style="background: #fef3c7; color: #d97706; font-size: 11px; font-weight: 600; padding: 1.5px 6px; border-radius: 4px; line-height: 1.2;">'.$daysLeft.' days</span>';
                } else {
                    $renewalHtml = '<span style="font-size: 12px; color: #334155; font-weight: 500;">'.$daysLeft.' days</span>';
                }

                // 7. Due Amount
                $hasDue = ($due_amount > 0);
                if ($hasDue) {
                    $dueHtml = '<span class="fcc-dues-flagged text-nowrap" style="background: #fee2e2; color: #dc2626; font-weight: 700; font-size: 11.5px; padding: 1.5px 6px; border-radius: 4px; display: inline-block; line-height: 1.2;">₹'.number_format($due_amount, 0).'</span>';
                } else {
                    $dueHtml = '<span class="text-muted text-nowrap" style="font-size: 12px; font-weight: 500;">₹0</span>';
                }

                // 8. Status
                if ($value->status == 0) {
                    $statusHtml = '<span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; line-height: 1.2;">Inactive</span>';
                } else {
                    $statusHtml = '<span class="badge" style="background: #dcfce7; color: #166534; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; line-height: 1.2;">Active</span>';
                }

                // 9. Actions
                $action = '<div class="dropdown custom-dropdown d-inline-block">
                    <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_'.$value->id.'" data-bs-toggle="dropdown" data-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config=\'{"strategy":"fixed"}\' aria-haspopup="true" aria-expanded="false" style="color: #2563eb; font-size: 14px; text-decoration: none; padding: 0; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 6px;">
                        <i class="fa fa-ellipsis-h" style="font-size: 14px; letter-spacing: 1.5px;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$value->id.'" style="border-radius: 12px; min-width: 230px; padding: 6px; height: auto !important; max-height: min(390px, 80vh) !important; overflow-y: auto !important; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif; white-space: nowrap;">
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
                    "DT_RowClass"       => ($hasDue ? 'fcc-row-dues-flagged' : ''),
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
            $user = User::create($data);

            if ($days > 0) {
                AttendanceLogs::create([
                    'user_id'    => $user->id,
                    'date'       => $startDate,
                    'remark'     => 'Add User Days',
                    'message'    => 'Initial plan allocation on registration',
                    'days'       => $days,
                    'total_days' => $days,
                    'created_by' => $authUser ? $authUser->id : 0,
                ]);
            }

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
                if (!is_null($user->profile_image)) {
                    delete_image(config('constants.users.image_path'), $user->profile_image);
                }
                //-----------------
                $image = $this->uploadImage($request->file('image'), config('constants.users.image_path'), null, 'users-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['profile_image'] = $imageName;
                }
            } elseif ($request->has('image_name') && empty($request->input('image_name')) && !empty($user->profile_image)) {
                delete_image(config('constants.users.image_path'), $user->profile_image);
                $data['profile_image'] = null;
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
        $authUser = auth()->user();
        $userUpdate = false;

        DB::beginTransaction();

        try {
            $user = User::where('id', dv($id))->first();
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $daysToAdd = max(1, (int)($request->input('days', 1)));
            $totalAmount = (float)($request->input('amount', 0));
            $receivedAmount = (float)($request->input('received_amount', 0));
            $dueAmount = max(0, $totalAmount - $receivedAmount);
            $paymentType = ($dueAmount > 0) ? 'Pending' : 'Received';
            $createdById = $authUser ? $authUser->id : null;

            // 1. Update user days & dues
            $newTotalDays = (int)($user->days ?? 0) + $daysToAdd;
            $user->days = $newTotalDays;
            if ($dueAmount > 0) {
                $user->due_amount = (float)($user->due_amount ?? 0) + $dueAmount;
            }
            $user->save();

            // 2. Attendance Logs
            $createdLog = AttendanceLogs::create([
                'user_id'       => $user->id,
                'date'          => date('Y-m-d'),
                'remark'        => 'Add User Days',
                'days'          => $daysToAdd,
                'message'       => $request->input('remark') ?? '',
                'total_days'    => $newTotalDays,
                'created_by'    => $createdById,
            ]);

            // 3. Transaction
            $transaction = [
                'user_id'           => $user->id,
                'title'             => 'Add User Days',
                'total_amount'      => $totalAmount,
                'received_amount'   => $receivedAmount,
                'due_amount'        => $dueAmount,
                'payment_type'      => $paymentType,
                'remark'            => $request->input('remark') ?? '',
                'created_by'        => $createdById,
            ];
            Transaction::create($transaction);

            $userUpdate = true;

            DB::commit();

            // 4. In-App Notification & Push Notification (isolated from main transaction)
            try {
                $senderData   = $authUser;
                $receiverData = $user;
                $senderName   = !empty($senderData->name) ? $senderData->name : 'Nutrihut';
                $receiverName = !empty($receiverData->name) ? $receiverData->name : 'Member';

                $title       = 'Days Added';
                $notiMessage = $receiverName . ', You’re all set ' . $daysToAdd . ' Days added.';

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData ? $senderData->id : 0,
                    'data_id'             => null,
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderName,
                    'receiver_name'       => $receiverName,
                    'notification_type'   => 1,
                ]);

                if (!empty($receiverData->fcm_token)) {
                    push_notification(
                        $receiverData->id,
                        $title,
                        $notiMessage,
                        $senderData ? $senderData->id : 0,
                        1,
                        $receiverData->fcm_token,
                        '',
                        $senderName,
                        $receiverName,
                        $receiverData->device_os
                    );
                }
            } catch (\Exception $pushEx) {
                \Log::warning('Notification error in updateUserDays: ' . $pushEx->getMessage());
            }

        } catch (\Exception $e) {
            $userUpdate = null;
            \Log::error('updateUserDays Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
        }

        // Response
        if (!empty($userUpdate)) {
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Add User Days']),
                '_type' => 'success',
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Add User Days']),
                '_type' => 'error',
            ];
        }

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
        $authUser = auth()->user();
        $userUpdate = false;

        DB::beginTransaction();

        try {
            $user = User::where('id', dv($id))->first();
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $daysToSubtract = max(1, (int)($request->input('days', 1)));
            $createdById = $authUser ? $authUser->id : null;

            // 1. Decrement user days
            $newTotalDays = max(0, (int)($user->days ?? 0) - $daysToSubtract);
            $user->days = $newTotalDays;
            $user->save();

            // 2. Attendance Logs
            $createdLog = AttendanceLogs::create([
                'user_id'       => $user->id,
                'date'          => date('Y-m-d'),
                'remark'        => 'Subtract User Days',
                'days'          => $daysToSubtract,
                'message'       => $request->input('remark') ?? '',
                'total_days'    => $newTotalDays,
                'created_by'    => $createdById,
            ]);

            $userUpdate = true;

            DB::commit();

            // 3. In-App Notification & Push Notification (isolated from main transaction)
            try {
                $senderData   = $authUser;
                $receiverData = $user;
                $senderName   = !empty($senderData->name) ? $senderData->name : 'Nutrihut';
                $receiverName = !empty($receiverData->name) ? $receiverData->name : 'Member';

                $title       = 'Days Subtract';
                $notiMessage = $receiverName . ', ' . $daysToSubtract . ' days are deducted from your subscription.';

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData ? $senderData->id : 0,
                    'data_id'             => null,
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderName,
                    'receiver_name'       => $receiverName,
                    'notification_type'   => 1,
                ]);

                if (!empty($receiverData->fcm_token)) {
                    push_notification(
                        $receiverData->id,
                        $title,
                        $notiMessage,
                        $senderData ? $senderData->id : 0,
                        1,
                        $receiverData->fcm_token,
                        '',
                        $senderName,
                        $receiverName,
                        $receiverData->device_os
                    );
                }
            } catch (\Exception $pushEx) {
                \Log::warning('Notification error in updateSubtractUserDays: ' . $pushEx->getMessage());
            }

        } catch (\Exception $e) {
            $userUpdate = null;
            \Log::error('updateSubtractUserDays Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
        }

        // Response
        if (!empty($userUpdate)) {
            $response = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Subtract User Days']),
                '_type' => 'success',
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Subtract User Days']),
                '_type' => 'error',
            ];
        }

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
                'raw_date' => date('Y-m-d', strtotime($w->date)),
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
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$value->id.'" style="border-radius: 12px; min-width: 180px; padding: 6px; height: auto !important; max-height: none !important; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif;">
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

        $attendancesRaw = Attendance::select('attendances.id as attendance_id', 'users.id', 'users.name', 'attendances.weight', 'attendances.date', 'attendances.type', 'attendances.created_at')
            ->leftJoin('users', function($join){
                $join->on('attendances.user_id', '=', 'users.id');
            })
            ->where("users.role_type", 'user')
            ->where('user_id', $user->id)
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('attendances.date', [$startDate, $endDate])
                  ->orWhere(function($sub) use ($startDate, $endDate) {
                      $sub->whereNull('attendances.date')
                          ->whereBetween('attendances.created_at', [$startDate, $endDate]);
                  });
            })
            ->orderBy(DB::raw('COALESCE(attendances.date, DATE(attendances.created_at))'),'ASC')
            ->get();

        $attendances = $attendancesRaw->keyBy(function ($item) {
            $d = !empty($item->date) ? $item->date : date('Y-m-d', strtotime($item->created_at));
            return Carbon::parse($d)->format('Y-m-d');
        });

        // 1. Total Check-ins (present)
        $presentAttendances = $attendancesRaw->filter(function($a) {
            return $a->type == 2;
        });
        $totalCheckIns = $presentAttendances->count();

        // 2. Last Check-in
        $lastAttendance = $presentAttendances->last();
        $lastDateVal = $lastAttendance ? (!empty($lastAttendance->date) ? $lastAttendance->date : $lastAttendance->created_at) : null;
        $lastCheckInDate = $lastDateVal ? date('d M Y', strtotime($lastDateVal)) : null;

        // 3. Recent Check-ins (last 8 dates)
        $recentCheckIns = $presentAttendances->take(-8)->map(function($a) {
            $d = !empty($a->date) ? $a->date : date('Y-m-d', strtotime($a->created_at));
            return (object)[
                'date' => $d,
                'formatted' => date('d M', strtotime($d)),
                'full' => date('d M Y', strtotime($d)),
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

        foreach ($presentAttendances as $att) {
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