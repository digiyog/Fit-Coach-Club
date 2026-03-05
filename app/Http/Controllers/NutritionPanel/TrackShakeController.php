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
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $sort   = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "user_id" => $request->user_id,
        );

        $user = User::where('status',1)->where('id', $request->user_id)->first();

        // Getting Track Shake Records
        $records_count  = AttendanceLogs::getAttendences(null, null, $search, $filter, $sort);
        $records        = AttendanceLogs::getAttendences($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $id             = $key+1;
                $name           = $user['name'];
                $total_days     = 'N/A';
                $days           = 'N/A';
                $remark         = 'N/A';
                $type           = 'N/A';
                $message        = 'N/A';
                $date           = 'N/A';
                
                // Preparing Data
                if(!empty($value->total_days))
                {
                    $total_days = $value->total_days;
                }

                if(!empty($value->days))
                {
                    $days = $value->days;
                }

                if(!empty($value->remark))
                {
                    $remark = $value->remark;
                }

                if($value->remark == 'QR Attendance Add'){
                    $type = 'App Side';
                } else {
                    $type = 'Admin Panel';
                }

                if(!empty($value->message))
                {
                    $message = $value->message;
                }

                if(!empty($value->date))
                {
                    $date = date("d-m-Y", strtotime($value->date));
                }

                // Array Data
                $arr_data[] = array(
                    "id"            => $id,
                    "name"          => $name,
                    "total_days"    => $total_days,
                    "days"          => $days,
                    "remark"        => $remark,
                    "type"          => $type,
                    'message'       => $message,
                    "date"          => $date,
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
}
