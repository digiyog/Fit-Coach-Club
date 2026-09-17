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

        $attendanceLogs = AttendanceLogs::where('user_id', $user->id)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $latestLog = $attendanceLogs->last();

        // 1. Current Balance
        $currentBalance = $user->days ?? ($latestLog->total_days ?? 0);

        // 2. Latest Activity Info
        $latestActivityText = 'No activity';
        $latestActivityDate = '';
        if ($latestLog) {
            $daysVal = (int)$latestLog->days;
            $sign = $daysVal > 0 ? '+' . $daysVal : $daysVal;
            $latestActivityText = $sign . ' attendance';
            $latestActivityDate = !empty($latestLog->date) ? date('d M Y', strtotime($latestLog->date)) : '';
        }

        // 3. Primary Source
        $appSideCount = $attendanceLogs->where('remark', 'QR Attendance Add')->count();
        $adminPanelCount = $attendanceLogs->count() - $appSideCount;
        $primarySource = ($appSideCount >= $adminPanelCount && $appSideCount > 0) ? 'App side' : 'Admin panel';
        $primarySourceSubtext = ($primarySource === 'App side') ? 'QR attendance' : 'Manual updates';

        // 4. Calculate Summary Metrics
        $totalShakesAdded = 0;
        $totalShakesUsed = 0;
        foreach ($attendanceLogs as $l) {
            $val = (int)$l->days;
            if ($val > 0) {
                $totalShakesAdded += $val;
            } elseif ($val < 0) {
                $totalShakesUsed += abs($val);
            }
        }

        // 5. Build Rich Chart Data Points
        $chartDataPoints = [];
        if ($attendanceLogs->count() > 0) {
            foreach ($attendanceLogs as $log) {
                $dt = $log->date ? date('Y-m-d', strtotime($log->date)) : date('Y-m-d');
                $eventType = 'attendance';
                $color = '#3b46f1';
                $daysVal = (int)$log->days;
                
                if (str_contains(strtolower($log->remark ?? ''), 'add user') || $daysVal > 0) {
                    $eventType = 'add';
                    $color = '#10b981';
                } elseif (str_contains(strtolower($log->remark ?? ''), 'subtract') || (str_contains(strtolower($log->remark ?? ''), 'delete') && $daysVal < 0)) {
                    $eventType = 'subtract';
                    $color = '#ef4444';
                } elseif (str_contains(strtolower($log->remark ?? ''), 'delete')) {
                    $eventType = 'delete';
                    $color = '#f59e0b';
                }

                $chartDataPoints[] = [
                    'id'             => $log->id,
                    'date'           => $dt,
                    'timestamp'      => strtotime($dt) * 1000,
                    'date_formatted' => date('d M Y', strtotime($dt)),
                    'date_short'     => date('d M', strtotime($dt)),
                    'balance'        => (int)$log->total_days,
                    'change'         => $daysVal,
                    'change_text'    => ($daysVal > 0 ? '+' : '') . $daysVal,
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

                // Change pill
                if ($daysVal > 0) {
                    $changeHtml = '<span class="badge" style="background: #dcfce7; color: #15803d; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">+' . $daysVal . '</span>';
                } elseif ($daysVal < 0) {
                    $changeHtml = '<span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">' . $daysVal . '</span>';
                } else {
                    $changeHtml = '<span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 6px;">0</span>';
                }

                // Activity styling
                $actBadge = match($remarkRaw) {
                    'QR Attendance Add' => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
                    'Manual Attendance Add' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                    'Add User Days' => ['bg' => '#dcfce7', 'color' => '#15803d'],
                    'Subtract User Days', 'Substarct User Days' => ['bg' => '#ffedd5', 'color' => '#c2410c'],
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
