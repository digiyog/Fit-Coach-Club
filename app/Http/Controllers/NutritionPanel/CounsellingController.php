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
            'Counsellings' => '',
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
        
        return view('nutrition-panel.counsellings.index')->with($this->viewData);
    }

    /**
     * Get Counsellings list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getCounsellings(Request $request)
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
            "date" => $request->date,
        );

        // Getting Counsellings Records
        $records_count  = Attendance::getCounsellings(null, null, $search, $filter, $sort);
        $records        = Attendance::getCounsellings($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $coach_name         = 'N/A';
                $attendanceCount    = $value->total_attendance;
                $pendingDays        = 'N/A';
                $date               = 'N/A';
                $current_meals      = 'N/A';
                
                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->coach_name)){
                    $coach_name = $value->coach_name;
                }

                if(!empty($value->days)){
                    $pendingDays = $value->days;
                }                

                if(!empty($value->date)){
                    $date = date("d-m-Y", strtotime($value->date)).'<br>'.date("h:i A", strtotime($value->created_at));
                }

                if(!empty($value->meal_type_name)){
                    $current_meals = $value->meal_type_name;
                }

                $action = '<div class="dropdown custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink6">
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.viewWeights', ['id' => ev($value->id)]).'">View Weight</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.viewAttendence', ['id' => ev($value->id)]).'">View Attendance</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.manual-attendences.manual-attendence', ['id' => ev($value->id)]).'">Manual Attendance</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.track-shake.index', ['id' => ev($value->id)]).'">Track Shake</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.orders.index', ['id' => ev($value->id)]).'">Purchase Products</a>
                        <a class="dropdown-item" href="'.route('nutritionPanel.users.details', ['id' => ev($value->id)]).'">View Details</a>
                    </div>
                </div>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "coach_name"        => $coach_name,
                    "attendance"        => $attendanceCount,
                    "days"              => $pendingDays,
                    "current_meals"     => $current_meals,
                    "date"              => $date,
                    'action'            => $action
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
