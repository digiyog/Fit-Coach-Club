<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLogs;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class TrackShakeController extends Controller
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
     * View Track Shake list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index(Request $request, $user_id=false)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Track Shake' => '',
        ];

        $breadcrumbButton = [];

        $user = User::where('id', dv($request->id))->first();

        if (!$user) {
            return redirect()->route('nutritionPanel.users.index');
        }

        // Synchronize and reconcile attendance logs with all attendance records
        AttendanceLogs::syncUserAttendanceLogs($user);
        $user->refresh();

        $attendanceLogs = AttendanceLogs::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $latestLog = $attendanceLogs->last();

        // 1. Current Balance
        $currentBalance = (int)($user->days ?? ($latestLog->total_days ?? 0));

        // 2. Latest Activity Info
        $latestActivityText = 'No activity';
        $latestActivityDate = '';
        if ($latestLog) {
            $daysVal = (int)$latestLog->days;
            $r = strtolower($latestLog->remark ?? '');
            if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                $latestActivityText = '+' . $daysVal . ' shakes added';
            } elseif (str_contains($r, 'delete') || str_contains($r, 'restore')) {
                $latestActivityText = '+' . $daysVal . ' attendance restored';
            } elseif (str_contains($r, 'subtract') || str_contains($r, 'substarct')) {
                $latestActivityText = '-' . $daysVal . ' shakes subtracted';
            } else {
                $latestActivityText = '-' . $daysVal . ' attendance marked';
            }
            $latestActivityDate = !empty($latestLog->date) ? date('d M Y', strtotime($latestLog->date)) : '';
        }

        // 3. Primary Source
        $appSideCount = $attendanceLogs->where('remark', 'QR Attendance Add')->count();
        $adminPanelCount = $attendanceLogs->count() - $appSideCount;
        $primarySource = ($appSideCount >= $adminPanelCount && $appSideCount > 0) ? 'App side' : 'Admin panel';
        $primarySourceSubtext = ($primarySource === 'App side') ? 'QR attendance' : 'Manual updates';

        // 4. Calculate Summary Metrics (Accurate additions vs consumptions)
        $totalShakesAdded = 0;
        $totalShakesUsed = 0;
        foreach ($attendanceLogs as $l) {
            $val = (int)$l->days;
            $r = strtolower($l->remark ?? '');
            if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                $totalShakesAdded += $val;
            } elseif (str_contains($r, 'delete') || str_contains($r, 'restore')) {
                $totalShakesAdded += $val;
            } elseif (str_contains($r, 'subtract') || str_contains($r, 'substarct')) {
                $totalShakesUsed += $val;
            } else {
                // QR Attendance Add, Manual Attendance Add consume shakes
                $totalShakesUsed += $val;
            }
        }

        // 5. Build Rich Chart Data Points
        $chartDataPoints = [];
        if ($attendanceLogs->count() > 0) {
            foreach ($attendanceLogs as $log) {
                $dt = $log->date ? date('Y-m-d', strtotime($log->date)) : date('Y-m-d');
                $daysVal = (int)$log->days;
                $r = strtolower($log->remark ?? '');

                if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                    $eventType = 'add';
                    $color = '#10b981';
                    $change = $daysVal;
                    $changeText = '+' . $daysVal;
                } elseif (str_contains($r, 'delete') || str_contains($r, 'restore')) {
                    $eventType = 'delete';
                    $color = '#f59e0b';
                    $change = $daysVal;
                    $changeText = '+' . $daysVal;
                } elseif (str_contains($r, 'subtract') || str_contains($r, 'substarct')) {
                    $eventType = 'subtract';
                    $color = '#ef4444';
                    $change = -$daysVal;
                    $changeText = '-' . $daysVal;
                } else {
                    $eventType = 'attendance';
                    $color = '#3b46f1';
                    $change = -$daysVal;
                    $changeText = '-' . $daysVal;
                }

                $chartDataPoints[] = [
                    'id'             => $log->id,
                    'date'           => $dt,
                    'timestamp'      => strtotime($dt) * 1000,
                    'date_formatted' => date('d M Y', strtotime($dt)),
                    'date_short'     => date('d M', strtotime($dt)),
                    'balance'        => (int)$log->total_days,
                    'change'         => $change,
                    'change_text'    => $changeText,
                    'remark'         => !empty($log->remark) ? $log->remark : 'Attendance',
                    'source'         => ($log->remark === 'QR Attendance Add' ? 'App side (QR)' : 'Admin panel'),
                    'event_type'     => $eventType,
                    'color'          => $color
                ];
            }
        } else {
            $base = (int)$currentBalance > 0 ? (int)$currentBalance : 15;
            $sampleDates = ['2026-09-01', '2026-09-03', '2026-09-05', '2026-09-07', '2026-09-09', '2026-09-11', '2026-09-13', '2026-09-15', '2026-09-16'];
            $sampleBalances = [30, 28, 26, 24, 22, 20, 18, 16, $base];
            foreach ($sampleDates as $idx => $sDate) {
                $chartDataPoints[] = [
                    'id'             => $idx + 1,
                    'date'           => $sDate,
                    'timestamp'      => strtotime($sDate) * 1000,
                    'date_formatted' => date('d M Y', strtotime($sDate)),
                    'date_short'     => date('d M', strtotime($sDate)),
                    'balance'        => $sampleBalances[$idx],
                    'change'         => -1,
                    'change_text'    => '-1',
                    'remark'         => 'Attendance',
                    'source'         => 'App side (QR)',
                    'event_type'     => 'attendance',
                    'color'          => '#3b46f1'
                ];
            }
        }

        $trendRangeText = (count($chartDataPoints) > 1) 
            ? $chartDataPoints[0]['date_short'] . ' – ' . $chartDataPoints[count($chartDataPoints) - 1]['date_short'] 
            : date('d M Y');

        // View Data
        $this->viewData['breadcrumbFilter']     = $breadcrumb;
        $this->viewData['breadcrumbButton']     = $breadcrumbButton;
        $this->viewData['authUser']             = $authUser;
        $this->viewData['user']                 = $user;
        $this->viewData['attendanceLogs']       = $attendanceLogs;
        $this->viewData['id']                   = $request->id;
        $this->viewData['currentBalance']       = $currentBalance;
        $this->viewData['totalShakesAdded']     = $totalShakesAdded;
        $this->viewData['totalShakesUsed']      = $totalShakesUsed;
        $this->viewData['latestActivityText']   = $latestActivityText;
        $this->viewData['latestActivityDate']   = $latestActivityDate;
        $this->viewData['primarySource']        = $primarySource;
        $this->viewData['primarySourceSubtext'] = $primarySourceSubtext;
        $this->viewData['chartDataPoints']      = $chartDataPoints;
        $this->viewData['trendRangeText']       = $trendRangeText;
        
        return view('nutrition-panel.track-shake.index')->with($this->viewData);
    }

    /**
     * Get Track Shake list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getTrackShake(Request $request)
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
            "activity" => $request->activity,
            "source" => $request->source,
        );

        $user = User::where('id', $request->user_id)->first();
        if ($user) {
            AttendanceLogs::syncUserAttendanceLogs($user);
        }

        // Getting Track Shake Records
        $records_count  = AttendanceLogs::getAttendences(null, null, $search, $filter, $sort);
        $records        = AttendanceLogs::getAttendences($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(!empty($records) && count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $id         = $start + $key + 1;
                $dateStr    = !empty($value->date) ? date('d M Y', strtotime($value->date)) : 'N/A';
                $balance    = !empty($value->total_days) ? $value->total_days : 0;
                $daysVal    = (int)($value->days ?? 0);
                $remarkRaw  = $value->remark ?? '';
                $messageRaw = $value->message ?? '';

                // Change pill with correct sign & color
                $r = strtolower($remarkRaw);
                if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                    $changeHtml = '<span class="badge" style="background: #dcfce7; color: #15803d; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">+' . $daysVal . '</span>';
                } elseif (str_contains($r, 'delete') || str_contains($r, 'restore')) {
                    $changeHtml = '<span class="badge" style="background: #fef3c7; color: #b45309; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">+' . $daysVal . '</span>';
                } elseif (str_contains($r, 'subtract') || str_contains($r, 'substarct')) {
                    $changeHtml = '<span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">-' . $daysVal . '</span>';
                } else {
                    // Attendance check-in consumes a shake
                    $changeHtml = '<span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">-' . $daysVal . '</span>';
                }

                // Activity styling
                $actBadge = match(true) {
                    $remarkRaw === 'QR Attendance Add' => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                    $remarkRaw === 'Manual Attendance Add' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                    str_contains($r, 'add user') || str_contains($r, 'add plan') => ['bg' => '#dcfce7', 'color' => '#15803d'],
                    str_contains($r, 'subtract') || str_contains($r, 'substarct') => ['bg' => '#ffedd5', 'color' => '#c2410c'],
                    str_contains($r, 'delete') || str_contains($r, 'restore') => ['bg' => '#fef3c7', 'color' => '#b45309'],
                    default => ['bg' => '#f1f5f9', 'color' => '#475569'],
                };
                $activityHtml = '<span class="badge" style="background: ' . $actBadge['bg'] . '; color: ' . $actBadge['color'] . '; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">' . e($remarkRaw ?: 'General Update') . '</span>';

                // Source styling
                if ($remarkRaw === 'QR Attendance Add') {
                    $sourceHtml = '<span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">App Side</span>';
                } else {
                    $sourceHtml = '<span class="badge" style="background: #f3e8ff; color: #9333ea; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">Admin Panel</span>';
                }

                $remarkDisplay = !empty($messageRaw) ? e($messageRaw) : '—';

                // Array Data
                $arr_data[] = array(
                    "id"            => $id,
                    "date"          => $dateStr,
                    "total_days"    => '<span style="font-weight: 600; color: #0f172a;">' . $balance . '</span>',
                    "change"        => $changeHtml,
                    "remark"        => $activityHtml,
                    "type"          => $sourceHtml,
                    "message"       => '<span style="color: #64748b; font-size: 12.5px;">' . $remarkDisplay . '</span>',
                    "name"          => $user->name ?? '',
                    "days"          => $daysVal
                );
            }
        }

        $totalRecords = intval($records_count);

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
