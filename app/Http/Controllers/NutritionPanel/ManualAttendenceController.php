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

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['user'] = $user;
        $this->viewData['attendanceLogs'] = $attendanceLogs;
        $this->viewData['id'] = $id;
        
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
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $sort   = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
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
                $id                 = $key+1;
                $weight             = 'N/A';
                $attendence_date    = 'N/A';
                $attendence_count   = 1;
                
                // Preparing Data
                if(!empty($value->date))
                {
                    $attendence_date = date("d-m-Y", strtotime($value->date));
                }

                if(!empty($value->weight))
                {
                    $weight = $value->weight;
                }

                $action = '<a herf="#" data-url="' . route('nutritionPanel.manual-attendences.destroy', ['id' => ev($value->id)]) . '" class="delete-attendence cursor-pointer" title="Delete Attendance"><div class="badge badge-danger"><i class="fa fa-trash"></i> Delete Attendance</div></a>';

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
    public function addManualAttendance(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $manualAttendence   = null;
        $errorMessage       = null;

        // Begin Transaction
        DB::beginTransaction();

        $user = User::find($request->user_id);

        if (!$user || $user->days < $request->days) {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => 'Not sufficient days to mark attendance.',
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->back()->with(['notification' => $notification]);
        }

        $startDate = Carbon::createFromFormat('d-m-Y', $request->date);
        $daysToMark = (int) $request->days;
        
        // Create Manual Attendance
        try {
            for ($i = 0; $i < $daysToMark; $i++) {
                $date = $startDate->copy()->addDays($i)->format('Y-m-d');

                $exists = Attendance::where('user_id', $user->id)
                    ->where('type', 2)
                    ->whereDate('date', $date)
                    ->exists();

                // if ($exists) {
                //     continue; // already marked, skip
                // }

                Attendance::create([
                    'franchise_id' => $authUser->id,
                    'user_id'      => $user->id,
                    'message'      => $request['remark'],
                    'date'         => $date,
                    'type'         => 2
                ]);

                $lastLog = AttendanceLogs::where('user_id', $user->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                $totalDays = $lastLog
                    ? $lastLog->total_days - 1
                    : $user->days - 1;

                AttendanceLogs::create([
                    'user_id'    => $user->id,
                    'date'       => $date,
                    'remark'     => 'Manual Attendance Add',
                    'message'    => $request['remark'],
                    'days'       => 1,
                    'total_days' => $totalDays,
                    'created_by' => $authUser->id,
                ]);

                $user->days--;
            }

            $user->save();

            DB::commit();

            // Set notification
            $notification = [
                '_status' => true,
                '_message' => $daysToMark . ' days attendance marked successfully.',
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->back()->with(['notification' => $notification]);
        } catch (\Exception $e) {
            $manualAttendence   = null;
            $errorMessage       = $e->getMessage();
            DB::rollback();

            // Set notification
            $notification = [
                '_status' => false,
                '_message' => 'Something went wrong.',
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->back()->with(['notification' => $notification]);
        }
        //------------
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

        $ids                = $request['id'];
        $lastAttendance     = Attendance::where('id', dv($ids))->first();
        $manualAttendence   = Attendance::where('id', dv($ids))->delete();

        $lastLog = AttendanceLogs::where('user_id', $lastAttendance->user_id)->orderBy('id', 'DESC')->first();

        AttendanceLogs::create([
            'user_id'    => $lastAttendance->user_id,
            'date'       => date('Y-m-d'),
            'remark'     => 'Attendance Delete',
            'days'       => 1,
            'total_days' => $lastLog['total_days']+1,
            'created_by' => $authUser->id,
        ]);

        User::where('id', $lastAttendance->user_id)->increment('days', 1);
        
        // Set response
        if ($manualAttendence == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Manual Attendance']),
                '_type' => 'success',
            ];
        }  else {
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
