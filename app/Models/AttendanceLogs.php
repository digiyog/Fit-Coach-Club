<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Http\Traits\HasSlug;

class AttendanceLogs extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable;
    
    protected $table = 'attendance_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    // Get Attendence list records
    public function scopeGetAttendences($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $attendences = AttendanceLogs::select('id', 'remark', 'date', 'days', 'total_days', 'message');
        if (!empty($filter['user_id'])) {
            $attendences->where("user_id", $filter['user_id']);
        }
        
        if (!empty($search)) {
            $searchStr = trim(strtolower($search));
            $attendences->where(function($q) use ($searchStr) {
                $q->whereRaw('lower(remark) LIKE ?', ["%{$searchStr}%"])
                  ->orWhereRaw('lower(message) LIKE ?', ["%{$searchStr}%"])
                  ->orWhereRaw('lower(date) LIKE ?', ["%{$searchStr}%"]);
            });
        }

        if (!empty($filter['activity'])) {
            if ($filter['activity'] === 'Manual Attendance Add') {
                $attendences->where(function($q) {
                    $q->where('remark', 'Manual Attendance Add')->orWhere('remark', 'Attendance');
                });
            } else {
                $attendences->where('remark', $filter['activity']);
            }
        }

        if (!empty($filter['source'])) {
            if ($filter['source'] === 'App Side') {
                $attendences->where('remark', 'QR Attendance Add');
            } elseif ($filter['source'] === 'Admin Panel') {
                $attendences->where('remark', '!=', 'QR Attendance Add');
            }
        }

        // Table columns sort conditions
        if(!(empty($sort)) && isset($sort['column']) && $sort['column'] > 0)
        {
            $arr_fields = array("", "id", "date", 'total_days', "days", "remark", "message");
            if (isset($arr_fields[$sort['column']]) && $arr_fields[$sort['column']] != "") {
                $attendences = $attendences->orderBy($arr_fields[$sort['column']], $sort['dir'] ?? 'DESC');
            }
        } else {
            $attendences = $attendences->orderBy('date', 'DESC')->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            return $attendences->skip($offset)->take($limit)->get();
        }
        else
        {
            return $attendences->count();
        }
    }

    /**
     * Synchronize and reconcile all attendance records and logs for a user.
     *
     * @param \App\Models\User|int $user
     * @return int
     */
    public static function syncUserAttendanceLogs($user)
    {
        if (is_numeric($user)) {
            $user = User::find($user);
        }
        if (!$user) {
            return 0;
        }

        // 1. Get all valid attendance records for this user (type = 2: Present)
        $attendances = Attendance::where('user_id', $user->id)
            ->where('type', 2)
            ->whereNull('deleted_at')
            ->orderBy(\DB::raw('COALESCE(date, DATE(created_at))'), 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        // Ensure each attendance has date populated
        foreach ($attendances as $att) {
            if (empty($att->date)) {
                $att->date = !empty($att->created_at) ? date('Y-m-d', strtotime($att->created_at)) : date('Y-m-d');
                $att->save();
            }
        }

        // 2. Fetch existing attendance logs
        $existingLogs = AttendanceLogs::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->get();

        // Find which attendance dates already have a check-in log
        $loggedAttendanceDates = [];
        foreach ($existingLogs as $log) {
            $r = strtolower($log->remark ?? '');
            $isAttendanceLog = (
                str_contains($r, 'attendance') &&
                !str_contains($r, 'delete') &&
                !str_contains($r, 'restore')
            );
            if ($isAttendanceLog && !empty($log->date)) {
                $d = date('Y-m-d', strtotime($log->date));
                $loggedAttendanceDates[$d] = true;
            }
        }

        // 3. Insert missing attendance logs for any attendance date not yet logged
        foreach ($attendances as $att) {
            $attDate = date('Y-m-d', strtotime($att->date));
            if (!isset($loggedAttendanceDates[$attDate])) {
                AttendanceLogs::create([
                    'user_id'    => $user->id,
                    'date'       => $attDate,
                    'remark'     => (!empty($att->message) && str_contains(strtolower($att->message), 'manual')) ? 'Manual Attendance Add' : 'Attendance',
                    'message'    => !empty($att->message) ? $att->message : 'Attendance marked',
                    'days'       => 1,
                    'total_days' => 0,
                    'created_by' => $att->franchise_id ?: ($att->created_by ?: ($user->created_by ?? 0)),
                    'created_at' => $att->created_at ?: \Carbon\Carbon::parse($attDate)->startOfDay(),
                ]);
                $loggedAttendanceDates[$attDate] = true;
            }
        }

        // 4. Retrieve all logs in strict chronological sequence (by date ASC, id ASC)
        $allLogs = AttendanceLogs::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        if ($allLogs->isEmpty()) {
            return (int)($user->days ?? 0);
        }

        // Check if there is an initial addition log
        $hasAdditionLog = false;
        foreach ($allLogs as $log) {
            $r = strtolower($log->remark ?? '');
            if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                $hasAdditionLog = true;
                break;
            }
        }

        // If no addition log exists at all, create an initial plan log from user registration
        if (!$hasAdditionLog) {
            $userDays = (int)($user->days ?? 0);
            $totalPresent = $attendances->count();
            $initialPlanDays = max($userDays + $totalPresent, 30);
            $regDate = !empty($user->created_at) ? date('Y-m-d', strtotime($user->created_at)) : ($allLogs->first()->date ?? date('Y-m-d'));

            $initLog = AttendanceLogs::create([
                'user_id'    => $user->id,
                'date'       => $regDate,
                'remark'     => 'Add User Days',
                'message'    => 'Initial plan allocation on registration',
                'days'       => $initialPlanDays,
                'total_days' => $initialPlanDays,
                'created_by' => $user->created_by ?? 0,
                'created_at' => $user->created_at ?: \Carbon\Carbon::parse($regDate)->startOfDay(),
            ]);

            $allLogs = AttendanceLogs::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->orderBy('date', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();
        }

        // 5. Recompute the running total_days ledger
        $runningBalance = 0;
        foreach ($allLogs as $log) {
            $daysVal = (int)$log->days;
            $r = strtolower($log->remark ?? '');

            if (str_contains($r, 'add user') || str_contains($r, 'add plan') || (str_contains($r, 'add') && !str_contains($r, 'attendance'))) {
                $runningBalance += $daysVal;
            } elseif (str_contains($r, 'delete') || str_contains($r, 'restore')) {
                $runningBalance += $daysVal;
            } elseif (str_contains($r, 'subtract') || str_contains($r, 'substarct')) {
                $runningBalance = max(0, $runningBalance - $daysVal);
            } else {
                // Attendance check-in consumes shakes
                $runningBalance = max(0, $runningBalance - $daysVal);
            }

            if ($log->total_days !== $runningBalance) {
                $log->total_days = $runningBalance;
                $log->save();
            }
        }

        // 6. Update user days to match final running balance
        $finalDays = max(0, $runningBalance);
        if ($user->days !== $finalDays) {
            $user->days = $finalDays;
            $user->save();
        }

        return $finalDays;
    }
}

