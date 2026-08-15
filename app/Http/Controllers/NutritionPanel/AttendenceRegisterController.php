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
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Attendance Register' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button',
            'btn_link' => 'javascript:;',
            'btn_icon' => 'filter',
            'btn_text' => __('language.filter'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.attendence-register.index')->with($this->viewData);
    }

    /**
     * Get Attendance Register list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getAttendenceRegister(Request $request)
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
            "month" => $request->month,
            "year" => $request->year,
        );

        $totalDays = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);

        // Getting Attendance Register Records
        $records_count  = Attendance::getAttendenceRegister(null, null, $search, $filter, $sort);
        $records        = Attendance::getAttendenceRegister($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name           = 'N/A';
                $total_days     = $totalDays;
                $total_present  = '0';
                $total_absent   = '0';
                
                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->total_present)){
                    $total_present = $value->total_present;
                }

                $total_absent = $totalDays - $value->total_present;

                $action = '<a herf="#" data-url="' . route('nutritionPanel.attendance-register.viewAttendance', ['id' => ev($value->id), 'month' => $request->month, 'year' => $request->year ]) . '" class="view-attendence cursor-pointer" title="View Attendance"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Attendance</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "total_days"        => $total_days,
                    "total_present"     => $total_present,
                    "total_absent"      => $total_absent,
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
     * View Description.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function viewAttendence($id, $month, $year)
    {
        $auth_user = auth()->user();

        // Get Attendance
        $attendances  = Attendance::where('user_id', dv($id))->whereMonth('date', $month)->whereYear('date', $year)->get()
        ->keyBy(function ($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
        });

        // Send view data
        $this->viewData['attendances']     = $attendances ;
        $this->viewData['month']           = $month;
        $this->viewData['year']            = $year;
        $this->viewData['daysInMonth']     = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return view('nutrition-panel.attendence-register.view-attendence')->with($this->viewData);
    }

}
