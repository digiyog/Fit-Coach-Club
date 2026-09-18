<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\DishType;
use App\Models\Attendance;
use App\Models\User;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AttendenceRegisterController extends Controller
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
     * View Attendance Register list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    */
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Attendance Register' => '',
        ];

        // Coaches list for filter
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->groupBy('coach_name')
            ->pluck('coach_name');

        // Initial Stats Calculation for Selected Month & Year
        $stats = $this->calculatePulseStats($selectedMonth, $selectedYear);

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['coachesList'] = $coachesList;
        $this->viewData['selectedMonth'] = $selectedMonth;
        $this->viewData['selectedYear'] = $selectedYear;
        $this->viewData['stats'] = $stats;
        
        return view('nutrition-panel.attendence-register.index')->with($this->viewData);
    }

    /**
     * Helper to compute attendance pulse stats
     */
    private function calculatePulseStats($month, $year, $coach_name = null)
    {
        $authUser = auth()->user();
        $totalDays = cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

        $query = User::where("role_type", 'user')
            ->where("created_by", $authUser->id);

        if (!empty($coach_name)) {
            $query->where('coach_name', $coach_name);
        }

        $allMembers = $query->withCount(['user_attendence as total_present' => function ($q) use ($month, $year) {
            $q->where('type', 2)->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        $totalMembers = $allMembers->count();
        $totalPresent = $allMembers->sum('total_present');
        $maxPossibleDays = $totalMembers * $totalDays;
        $totalAbsent = max(0, $maxPossibleDays - $totalPresent);
        
        $avgRate = $maxPossibleDays > 0 ? round(($totalPresent / $maxPossibleDays) * 100) : 0;
        $presentPct = $maxPossibleDays > 0 ? round(($totalPresent / $maxPossibleDays) * 100) : 0;
        $absentPct = max(0, 100 - $presentPct);

        // Top consistency
        $maxPresent = $allMembers->max('total_present') ?? 0;
        $topMembers = $maxPresent > 0 ? $allMembers->where('total_present', $maxPresent)->pluck('name')->take(2)->implode(' • ') : 'None';

        // Needs attention (0 check-ins)
        $zeroCheckins = $allMembers->where('total_present', 0);
        $needsAttentionCount = $zeroCheckins->count();
        $needsAttentionList = [];
        
        $avatarColors = ['#f3e8ff', '#e0e7ff', '#ede9fe', '#fee2e2', '#ffedd5', '#dbeafe', '#dcfce7'];
        $textColors = ['#7c3aed', '#4338ca', '#6d28d9', '#dc2626', '#ea580c', '#2563eb', '#16a34a'];

        foreach ($zeroCheckins->take(3) as $idx => $zc) {
            $colorIdx = abs(crc32($zc->name ?? 'User')) % count($avatarColors);
            $needsAttentionList[] = [
                'id' => ev($zc->id),
                'name' => $zc->name ?? 'User',
                'initial' => strtoupper(substr(trim($zc->name ?? 'U'), 0, 1) ?: 'U'),
                'bg_color' => $avatarColors[$colorIdx],
                'text_color' => $textColors[$colorIdx],
                'total_days' => $totalDays,
            ];
        }

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F');

        return [
            'month_name' => $monthName,
            'year' => $year,
            'total_days' => $totalDays,
            'total_members' => $totalMembers,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'avg_rate' => $avgRate,
            'present_pct' => $presentPct,
            'absent_pct' => $absentPct,
            'top_consistency_days' => $maxPresent,
            'top_consistency_names' => $topMembers,
            'needs_attention_count' => $needsAttentionCount,
            'needs_attention_list' => $needsAttentionList
        ];
    }

    /**
     * Get Attendance Register list.
     *
     * @return response
    */
    public function getAttendenceRegister(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $limit  = $request->get('length', 20);
        $sort   = $request->get('order')[0] ?? [];
        $search = $request->get('search')['value'] ?? '';
        
        $month = $request->month ?: date('m');
        $year = $request->year ?: date('Y');
        $coachName = $request->coach_name;
        $attendanceStatus = $request->attendance_status;

        // Filter Parameters
        $filter = array(
            "month" => $month,
            "year" => $year,
            "coach_name" => $coachName,
            "attendance_status" => $attendanceStatus
        );

        $totalDays = cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

        // Getting Attendance Register Records
        $records_count  = Attendance::getAttendenceRegister(null, null, $search, $filter, $sort);
        $records        = Attendance::getAttendenceRegister($limit, $start, $search, $filter, $sort);

        // Stats calculation for the current filter/month
        $stats = $this->calculatePulseStats($month, $year, $coachName);

        $arr_data = array();

        $avatarColors = ['#f3e8ff', '#e0e7ff', '#ede9fe', '#fee2e2', '#ffedd5', '#dbeafe', '#dcfce7', '#fae8ff'];
        $textColors = ['#7c3aed', '#4338ca', '#6d28d9', '#dc2626', '#ea580c', '#2563eb', '#16a34a', '#a21caf'];

        if (count($records) > 0)
        {
            foreach ($records as $key => $value)
            {
                $name           = !empty($value->name) ? $value->name : 'N/A';
                $total_days     = $totalDays;
                $total_present  = !empty($value->total_present) ? intval($value->total_present) : 0;
                $total_absent   = max(0, $totalDays - $total_present);
                $ratePct        = $total_days > 0 ? round(($total_present / $total_days) * 100) : 0;
                
                $colorIdx = abs(crc32($name)) % count($avatarColors);
                $initial = strtoupper(substr(trim($name), 0, 1) ?: 'U');
                $bgColor = $avatarColors[$colorIdx];
                $textColor = $textColors[$colorIdx];

                // 1. Member column with Avatar
                $memberCol = '<div class="fcc-member-cell d-flex align-items-center gap-2">' .
                    '<div class="fcc-avatar-circle" style="background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $initial . '</div>' .
                    '<span class="fcc-member-name fw-semibold">' . e($name) . '</span>' .
                    '</div>';

                // 2. Attendance with mini progress bar
                $barWidth = min(100, max(0, $ratePct));
                $attendanceCol = '<div class="fcc-attendance-cell d-flex align-items-center gap-2">' .
                    '<span class="fcc-attendance-label text-nowrap">' . $total_present . ' of ' . $total_days . ' days</span>' .
                    '<div class="fcc-mini-progress">' .
                    '<div class="fcc-mini-bar" style="width: ' . $barWidth . '%;"></div>' .
                    '</div>' .
                    '</div>';

                // 3. Present column with green dot
                $presentCol = '<span class="fcc-stat-dot text-nowrap"><span class="fcc-dot fcc-dot-green"></span> ' . $total_present . '</span>';

                // 4. Absent column with coral dot
                $absentCol = '<span class="fcc-stat-dot text-nowrap"><span class="fcc-dot fcc-dot-coral"></span> ' . $total_absent . '</span>';

                // 5. Rate column
                $rateCol = '<span class="fcc-rate-text fw-semibold">' . $ratePct . '%</span>';

                // 6. Follow-up priority badge
                if ($total_present === 0) {
                    $followUpCol = '<span class="fcc-badge fcc-badge-danger">No check-ins</span>';
                } elseif ($ratePct <= 20) {
                    $followUpCol = '<span class="fcc-badge fcc-badge-warning">Low</span>';
                } elseif ($ratePct <= 50) {
                    $followUpCol = '<span class="fcc-badge fcc-badge-purple">Building</span>';
                } else {
                    $followUpCol = '<span class="fcc-badge fcc-badge-success">On track</span>';
                }

                // 7. Action column
                $actionUrl = route('nutritionPanel.attendance-register.viewAttendance', [
                    'id' => ev($value->id),
                    'month' => $month,
                    'year' => $year
                ]);
                $actionCol = '<a href="javascript:void(0);" data-url="' . $actionUrl . '" class="fcc-btn-view-attendance view-attendence" title="View Attendance">' .
                    '<i class="fa fa-eye me-1"></i> View attendance <i class="fa fa-chevron-right ms-1 fcc-chevron-icon"></i>' .
                    '</a>';

                // Array Data
                $arr_data[] = array(
                    "member"            => $memberCol,
                    "attendance"        => $attendanceCol,
                    "total_present"     => $presentCol,
                    "total_absent"      => $absentCol,
                    "rate"              => $rateCol,
                    "follow_up"         => $followUpCol,
                    "action"            => $actionCol,
                    "raw_name"          => $name,
                    "raw_present"       => $total_present,
                    "raw_absent"        => $total_absent,
                    "raw_rate"          => $ratePct,
                );
            }
        }

        $totalRecords = $records_count;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data,
            "stats"                 => $stats
        );

        return response()->json($response);
    }

    /**
     * View Attendance Modal.
     *
     * @return response
     */
    public function viewAttendence($id, $month, $year)
    {
        $auth_user = auth()->user();
        $userId = dv($id);
        $user = User::find($userId);

        // Get Attendance
        $attendances = Attendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        // Send view data
        $this->viewData['user']            = $user;
        $this->viewData['attendances']     = $attendances;
        $this->viewData['month']           = $month;
        $this->viewData['year']            = $year;
        $this->viewData['daysInMonth']     = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $this->viewData['firstDayOfWeek']  = \Carbon\Carbon::createFromDate($year, $month, 1)->dayOfWeek;

        return view('nutrition-panel.attendence-register.view-attendence')->with($this->viewData);
    }

}
