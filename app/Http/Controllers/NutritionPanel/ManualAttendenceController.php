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

class ManualAttendenceController extends Controller
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
     * View Manual Attendance list.
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
            'Manual Attendance' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button

        $user = User::where('status',1)->where('id', dv($request->id))->first();
        $attendanceLogs = AttendanceLogs::where('user_id', dv($request->id))->orderBy('id', 'DESC')->first();

        $lastAttendance = Attendance::where('user_id', $user->id ?? 0)
            ->where('type', 2)
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $latestWeightRecord = Attendance::where('user_id', $user->id ?? 0)
            ->whereNotNull('weight')
            ->where('weight', '!=', '')
            ->where('weight', '>', 0)
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $lastAttendanceDate = $lastAttendance ? date('d M Y', strtotime($lastAttendance->date)) : null;
        $latestWeight = $latestWeightRecord ? (float)$latestWeightRecord->weight : (!empty($user->current_weight) ? (float)$user->current_weight : 0);

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['user'] = $user;
        $this->viewData['attendanceLogs'] = $attendanceLogs;
        $this->viewData['lastAttendanceDate'] = $lastAttendanceDate;
        $this->viewData['latestWeight'] = $latestWeight;
        
        return view('nutrition-panel.manual-attendences.index')->with($this->viewData);
    }

    /**
     * Get Manual Attendance list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getManualAttendence(Request $request)
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
        );

        // Getting Manual Attendance Records
        $records_count  = Attendance::getAttendences(null, null, $search, $filter, $sort);
        $records        = Attendance::getAttendences($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $id                 = $start + $key + 1;
                $weight             = 'N/A';
                $attendence_date    = 'N/A';
                $attendence_count   = 1;
                
                // Preparing Data
                if(!empty($value->date))
                {
                    $attendence_date = date("d M Y", strtotime($value->date));
                }

                if(!empty($value->weight) && (float)$value->weight > 0)
                {
                    $weight = number_format((float)$value->weight, 1) . ' kg';
                }

                $action = '<a href="javascript:;" data-url="' . route('nutritionPanel.manual-attendances.destroy', ['id' => ev($value->id)]) . '" class="fcc-btn-delete-attendance delete-attendence cursor-pointer" title="Delete Attendance"><i class="fa fa-trash-o me-1"></i> Delete</a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $id,
                    "attendence_date"   => $attendence_date,
                    "weight"            => $weight,
                    "attendence_count"  => $attendence_count,
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
     * Store Manual Attendance.
     *
     * @return mixed
     *
     * @author Sandeep
     * @created 24 Jan 2023
     */
    public function addManualAttendence(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $manualAttendence   = null;
        $errorMessage       = null;

        $user = User::find($request->user_id);

        if (!$user) {
            $notification = [
                '_status' => false,
                '_message' => 'User not found.',
                '_type' => 'error',
            ];
            return redirect()->back()->with(['notification' => $notification]);
        }

        $daysToMark = max(1, (int)($request->input('days', 1)));

        if ($user->days <= 0) {
            $notification = [
                '_status' => false,
                '_message' => 'Not sufficient days to mark attendance.',
                '_type' => 'error',
            ];
            return redirect()->back()->with(['notification' => $notification]);
        }

        // Robust date parsing (supports d-m-Y, Y-m-d, d/m/Y, etc.)
        $rawDate = trim($request->date ?? '');
        $startDate = null;

        if (!empty($rawDate)) {
            try {
                $startDate = Carbon::createFromFormat('d-m-Y', $rawDate)->startOfDay();
            } catch (\Exception $e) {
                try {
                    $startDate = Carbon::parse($rawDate)->startOfDay();
                } catch (\Exception $e2) {
                    $startDate = null;
                }
            }
        }

        if (!$startDate) {
            $notification = [
                '_status' => false,
                '_message' => 'Please provide a valid attendance date.',
                '_type' => 'error',
            ];
            return redirect()->back()->with(['notification' => $notification]);
        }

        $remark = $request->input('remark') ?? '';
        $weight = $request->filled('weight') ? (float)$request->input('weight') : null;

        // Begin Transaction
        DB::beginTransaction();

        try {
            $markedCount = 0;
            $skippedCount = 0;

            $markedDates = [];

            for ($i = 0; $i < $daysToMark; $i++) {
                $date = $startDate->copy()->addDays($i)->format('Y-m-d');

                $exists = Attendance::where('user_id', $user->id)
                    ->where('type', 2)
                    ->whereDate('date', $date)
                    ->whereNull('deleted_at')
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue; // Skip duplicate check-in to prevent double-deduction
                }

                $attData = [
                    'franchise_id' => $authUser ? $authUser->id : ($user->created_by ?? 0),
                    'user_id'      => $user->id,
                    'message'      => $remark,
                    'date'         => $date,
                    'type'         => 2,
                ];

                if ($weight !== null && $weight > 0) {
                    $attData['weight'] = $weight;
                }

                Attendance::create($attData);
                $markedDates[] = $date;
                $markedCount++;
            }

            if ($markedCount === 0 && $skippedCount > 0) {
                DB::rollBack();
                $notification = [
                    '_status' => false,
                    '_message' => 'Attendance for the selected date(s) has already been marked.',
                    '_type' => 'error',
                ];
                return redirect()->back()->with(['notification' => $notification]);
            }

            // Decrement user pending days by marked count
            $runningDays = (int)($user->days ?? 0);

            // Create AttendanceLog for each marked date
            foreach ($markedDates as $mDate) {
                $runningDays = max(0, $runningDays - 1);
                AttendanceLogs::create([
                    'user_id'    => $user->id,
                    'date'       => $mDate,
                    'remark'     => 'Manual Attendance Add',
                    'message'    => $remark ?: 'Manual Attendance marked',
                    'days'       => 1,
                    'total_days' => $runningDays,
                    'created_by' => $authUser ? $authUser->id : ($user->created_by ?? 0),
                ]);
            }

            $user->days = $runningDays;

            // Update user weight if provided
            if ($weight !== null && $weight > 0) {
                $user->current_weight = $weight;
            }
            $user->save();

            // Re-sync user attendance logs to ensure 100% ledger consistency
            AttendanceLogs::syncUserAttendanceLogs($user);

            DB::commit();

            $msg = $markedCount . ' day(s) attendance marked successfully.';
            if ($skippedCount > 0) {
                $msg .= ' (' . $skippedCount . ' already marked date(s) skipped)';
            }

            $notification = [
                '_status' => true,
                '_message' => $msg,
                '_type' => 'success',
            ];
            return redirect()->back()->with(['notification' => $notification]);

        } catch (\Exception $e) {
            \Log::error('ManualAttendence Error: ' . $e->getMessage());
            DB::rollback();

            $notification = [
                '_status' => false,
                '_message' => 'Something went wrong while marking attendance.',
                '_type' => 'error',
            ];
            return redirect()->back()->with(['notification' => $notification]);
        }
    }

    /**
     * Store Today Weight.
     *
     * @return mixed
     *
     * @author Sandeep
     * @created 24 Jan 2023
     */
    public function addTodayWeight(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Today Weight
        try {
            $user       = User::find($request->user_id);
            $date       = Carbon::parse($request->date)->toDateString();
            $exists     = Attendance::where('user_id', $user->id)->where('type', 2)->whereDate('date', $date)->exists();

            if ($exists) {
                Attendance::where('user_id', $user->id)->whereDate('date', $date)->update([
                    'weight' => $request->weight,
                ]);

                $status = 1;
            } else {
                $status = 2;
            }

            DB::commit();
        } catch (\Exception $e) {
            $status = 3;
            $errorMessage = $e->getMessage();
            \Log::error('ManualAttendence update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if ($status == 1) {

            $notification = [
                '_status'  => true,
                '_message' => 'Weight has been added successfully.',
                '_type'    => 'success',
            ];

            return redirect()->back()->with(['notification' => $notification]);

        } elseif ($status == 2) {

            $notification = [
                '_status'  => false,
                '_message' => 'Attendance has not been marked yet. Please mark attendance first.',
                '_type'    => 'error',
            ];

            return redirect()->back()->with(['notification' => $notification]);

        } else {

            $notification = [
                '_status'  => false,
                '_message' => 'Something went wrong. Please try again.',
                '_type'    => 'error',
            ];

            return redirect()->back()->with(['notification' => $notification]);
        }

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
        // Get user
        $authUser = auth()->user();
        //----------

        $ids            = $request['id'];
        $attendanceId   = dv($ids);
        $lastAttendance = Attendance::where('id', $attendanceId)->first();

        if (!$lastAttendance) {
            return response()->json([
                '_status' => false,
                '_message' => 'Attendance record not found.',
                '_type' => 'error',
            ], 404);
        }

        $userId = $lastAttendance->user_id;
        $user   = User::find($userId);

        $manualAttendence = $lastAttendance->delete();

        $newPendingDays = 0;
        if ($user) {
            $newPendingDays = (int)($user->days ?? 0) + 1;
            $user->days = $newPendingDays;
            $user->save();

            AttendanceLogs::create([
                'user_id'    => $userId,
                'date'       => date('Y-m-d'),
                'remark'     => 'Attendance Delete',
                'message'    => 'Attendance deleted',
                'days'       => 1,
                'total_days' => $newPendingDays,
                'created_by' => $authUser ? $authUser->id : 0,
            ]);

            AttendanceLogs::syncUserAttendanceLogs($user);
        }
        
        // Set response
        if ($manualAttendence) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Manual Attendance']),
                '_type' => 'success',
            ];
        } else {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Manual Attendance']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

}
