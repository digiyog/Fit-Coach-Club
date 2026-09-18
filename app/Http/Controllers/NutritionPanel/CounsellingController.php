<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Attendance;
use App\Models\User;
use App\Models\MealType;
use App\Models\AttendanceLogs;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CounsellingController extends Controller
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
     * View Counsellings list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Counsellings' => '',
        ];

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->groupBy('coach_name')
            ->pluck('coach_name');

        // Meal types
        $mealTypes = MealType::where('status', 1)->orderBy('name')->get();
        $mealPlansCount = $mealTypes->count();

        // Completed count today
        $todayCompletedCount = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where('date', date('Y-m-d'))
            ->count();

        // Total dues flagged for franchise members
        $duesFlagged = User::where('created_by', $authUser->id)
            ->where('due_amount', '>', 0)
            ->sum('due_amount');

        // Last session timestamp today
        $lastAttendance = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where('date', date('Y-m-d'))
            ->latest('created_at')
            ->first();

        $lastSessionTime = $lastAttendance && !empty($lastAttendance->created_at) 
            ? date('h:i A', strtotime($lastAttendance->created_at)) 
            : '09:41 AM';

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['todayCompletedCount'] = $todayCompletedCount;
        $this->viewData['mealPlansCount'] = $mealPlansCount;
        $this->viewData['duesFlagged'] = $duesFlagged;
        $this->viewData['lastSessionTime'] = $lastSessionTime;
        $this->viewData['coachesList'] = $coachesList;
        $this->viewData['mealTypes'] = $mealTypes;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.counsellings.index')->with($this->viewData);
    }

    /**
     * Get Counsellings list.
     *
     * @return response
    */
    public function getCounsellings(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start') ? intval($request->get('start')) : 0;
        $limit  = $request->get('length') ? intval($request->get('length')) : 25;
        $sort   = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'] ?? null;
        
        // Filter Parameters
        $filter = array(
            "name" => $request->name,
            "coach_name" => $request->coach_name,
            "plan_id" => $request->plan_id,
            "date" => $request->date,
            "tab" => $request->tab,
        );

        $arr_data = array();
        $totalRecords = 0;

        try {
            // Getting Counsellings Records
            $records_count  = Attendance::getCounsellings(null, null, $search, $filter, $sort);
            $records        = Attendance::getCounsellings($limit, $start, $search, $filter, $sort);
            $totalRecords   = intval($records_count);

            if(!empty($records) && count($records) > 0)
            {
                $colors = [
                    ['bg' => '#e0e7ff', 'color' => '#3b46f1'], // Blue
                    ['bg' => '#f3e8ff', 'color' => '#9333ea'], // Purple
                    ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
                    ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
                    ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber / Yellow
                    ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
                ];

                foreach($records as $key => $value)
                {
                    $userId         = $value->id ?? 0;
                    $name           = !empty($value->name) ? $value->name : 'N/A';
                    $coach_name     = !empty($value->coach_name) ? $value->coach_name : 'N/A';
                    $attendanceCount= $value->total_attendance ?? 1;
                    $pendingDays    = $value->days ?? 0;
                    $current_meals  = !empty($value->meal_type_name) ? $value->meal_type_name : '';
                    $due_amount     = (float)($value->due_amount ?? 0);
                    $joinedDate     = !empty($value->user_created_at) ? date('d M Y', strtotime($value->user_created_at)) : '12 Sep 2026';
                    $completedAt    = !empty($value->attendance_time) ? date('H:i:s', strtotime($value->attendance_time)) : '-';

                    // 1. Initials and Avatar
                    $initials = '';
                    $nameWords = explode(' ', trim($name));
                    foreach ($nameWords as $w) {
                        if (!empty($w)) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 1);
                    if (empty($initials)) $initials = 'M';

                    $cIdx = abs(crc32($name)) % count($colors);
                    $avatarCol = $colors[$cIdx];

                    // Member HTML with Initial and Joined Date
                    $memberHtml = '<div class="d-flex align-items-center gap-2">
                        <div class="fcc-avatar-circle" style="width: 34px; height: 34px; min-width: 34px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>
                        <div>
                            <div class="fcc-member-name fw-bold" style="color: #0f172a; font-size: 13px; line-height: 1.2;">'.e($name).'</div>
                            <div class="fcc-member-joined text-muted" style="font-size: 11px; margin-top: 2px;">Joined: '.$joinedDate.'</div>
                        </div>
                    </div>';

                    // 2. Attendance Count (Att.)
                    $attHtml = '<div class="fcc-att-pill">'.$attendanceCount.'</div>';

                    // 3. Coach Name
                    $coachHtml = '<span class="fcc-coach-name">'.e($coach_name).'</span>';

                    // 4. Plan
                    $planHtml = '<span class="fcc-plan-name">'.e(!empty($current_meals) ? $current_meals : '21 Days Challenge (Loss)').'</span>';

                    // 5. Pending Days
                    $pendingHtml = '<div class="fcc-pending-pill">'.$pendingDays.'</div>';

                    // 6. Progress (Weight, Recent Diff between previous day and current day, Total Diff from start)
                    $curWeight = (float)($value->attendance_weight ?: ($value->current_weight ?: 0));
                    $startWeight = (float)($value->starting_weight ?: 0);
                    $prevWeight = !empty($value->previous_weight) ? (float)$value->previous_weight : 0;

                    if ($curWeight > 0) {
                        $weightDisplay = number_format($curWeight, ($curWeight == (int)$curWeight ? 1 : 2)) . ' kg';
                    } else {
                        $weightDisplay = '-';
                    }

                    // Total diff against starting weight
                    if ($curWeight > 0 && $startWeight > 0) {
                        $totalDiff = round($curWeight - $startWeight, 2);
                        if ($totalDiff < 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.abs($totalDiff).' kg</span>';
                        } elseif ($totalDiff > 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.abs($totalDiff).' kg</span>';
                        } else {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                        }
                    } else {
                        $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                    }

                    // Recent diff: difference between current day and previous day/session weight
                    if ($curWeight > 0 && $prevWeight > 0) {
                        $diffKg = round($curWeight - $prevWeight, 3);
                        if ($diffKg < 0) {
                            $absDiff = abs($diffKg);
                            if ($absDiff < 1.0) {
                                $grams = intval(round($absDiff * 1000));
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.$grams.' g</span>';
                            } else {
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.$absDiff.' kg</span>';
                            }
                        } elseif ($diffKg > 0) {
                            if ($diffKg < 1.0) {
                                $grams = intval(round($diffKg * 1000));
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.$grams.' g</span>';
                            } else {
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.$diffKg.' kg</span>';
                            }
                        } else {
                            $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 g</span>';
                        }
                    } else {
                        $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 g</span>';
                    }

                    $progressHtml = '<div class="d-flex align-items-center gap-1 flex-nowrap text-nowrap" style="white-space: nowrap;">
                        <span class="fcc-weight-val">'.$weightDisplay.'</span>
                        '.$recentDiffHtml.'
                        '.$totalDiffHtml.'
                    </div>';

                    // 7. Dues
                    if ($due_amount > 0) {
                        $duesHtml = '<span class="fcc-dues-flagged text-nowrap">₹'.number_format($due_amount, 0).'</span>';
                    } else {
                        $duesHtml = '<span class="text-muted text-nowrap" style="font-size: 13px;">₹0</span>';
                    }

                    // Encrypted ID for URLs
                    $encryptedId = $userId ? ev($userId) : '';

                    // 8. Meal Button
                    if (!empty($current_meals) && $userId) {
                        $mealHtml = '<a href="'.route('nutritionPanel.users.details', ['id' => $encryptedId]).'" class="btn fcc-btn-view-meal text-nowrap" style="white-space: nowrap;">View meal</a>';
                    } else {
                        $mealHtml = '<span class="text-muted text-nowrap" style="font-size: 13px; white-space: nowrap;">No meal</span>';
                    }

                    // 9. Completed At
                    $completedAtHtml = '<span style="font-size: 12.5px; color: #334155; font-weight: 500;">'.$completedAt.'</span>';

                    // 10. Actions Dropdown matching modern 3-dots
                    $action = '<div class="dropdown custom-dropdown d-inline-block">
                        <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_'.$userId.'" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$userId.'" style="border-radius: 12px; min-width: 195px; padding: 6px; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif;">
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewWeights', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-balance-scale me-2 text-muted"></i> View Weight</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewAttendance', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $encryptedId]) : route('nutritionPanel.manual-attendances.manual-attendance')).'"><i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.track-shake.index', ['id' => $encryptedId]) : route('nutritionPanel.track-shake.index')).'"><i class="fa fa-coffee me-2 text-muted"></i> Track Shake</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.orders.index').'"><i class="fa fa-shopping-cart me-2 text-muted"></i> Purchase Products</a>
                            <div class="dropdown-divider my-1"></div>
                            <a class="dropdown-item py-2 px-3 rounded-2 text-primary fw-bold" href="'.($userId ? route('nutritionPanel.users.details', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-id-card-o me-2 text-primary"></i> View Details</a>
                        </div>
                    </div>';

                    // Row Checkbox
                    $checkboxHtml = '<label class="fcc-custom-checkbox m-0"><input type="checkbox" class="fcc-checkbox-input child-chk" value="'.$userId.'"><span class="fcc-checkbox-control"><svg viewBox="0 0 12 10" class="fcc-check-icon"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span></label>';

                    // Array Data
                    $arr_data[] = array(
                        "DT_RowClass"       => ($due_amount > 0 ? 'fcc-row-dues-flagged' : ''),
                        "id"                => $userId,
                        "checkbox"          => $checkboxHtml,
                        "name"              => $memberHtml,
                        "att"               => $attHtml,
                        "attendance"        => $attHtml,
                        "coach_name"        => $coachHtml,
                        "plan"              => $planHtml,
                        "days"              => $pendingHtml,
                        "progress"          => $progressHtml,
                        "dues"              => $duesHtml,
                        "meal"              => $mealHtml,
                        "current_meals"     => $mealHtml,
                        "completed_at"      => $completedAtHtml,
                        "date"              => $completedAtHtml,
                        "action"            => $action
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Counselling getCounsellings error: ' . $e->getMessage());
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
}
