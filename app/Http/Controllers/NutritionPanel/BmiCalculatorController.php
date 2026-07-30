<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Models\BmiCalculator;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class BmiCalculatorController extends Controller
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
     * View Bmi Calculator list.
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
            'Bmi Calculator' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        // $breadcrumbButton[] = [
        //     'btn_class' => 'btn btn-primary mt-2 rounded-circle',
        //     'btn_link' => route('nutritionPanel.bmi-calculator.create'),
        //     'btn_icon' => 'plus',
        //     'btn_text' => __('language.add_button'),
        //     'attributes' => []
        // ];

        $users = User::where('status',1)->where('created_by', $authUser['id'])->orderBy('id', 'DESC')->get();

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['users'] = $users;
        $this->viewData['id'] = $id;
        
        return view('nutrition-panel.bmi-calculator.index')->with($this->viewData);
    }

    /**
     * Get Bmi Calculator list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getBmiCalculator(Request $request)
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
        );

        // Getting Bmi Calculator Records
        $records_count  = BmiCalculator::getBmiCalculator(null, null, $search, $filter, $sort);
        $records        = BmiCalculator::getBmiCalculator($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $mobile_number      = 'N/A';
                $age                = 'N/A';
                $weight             = 'N/A';
                $height             = 'N/A';
                $gender             = 'N/A';
                $bmi                = 'N/A';
                $body_fat           = 'N/A';
                $visceral_fat       = 'N/A';
                $muscle_mass        = 'N/A';
                $metabolic_rate     = 'N/A';
                $biologic_age       = 'N/A';
                $body_age           = 'N/A';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->mobile_number)){
                    $mobile_number = $value->mobile_number;
                }

                if(!empty($value->age)){
                    $age = $value->age;
                }

                if(!empty($value->weight)){
                    $weight = $value->weight;
                }

                if(!empty($value->height)){
                    $height = $value->height;
                }

                if($value->gender == 1){
                    $gender = 'Male';
                } else {
                    $gender = 'Female';
                }

                if(!empty($value->bmi)){
                    $bmi = round($value->bmi, 2);
                }

                if(!empty($value->body_fat)){
                    $body_fat = round($value->body_fat, 2);
                }

                if(!empty($value->visceral_fat)){
                    $visceral_fat = round($value->visceral_fat, 1);
                }

                if(!empty($value->muscle_mass)){
                    $muscle_mass = round($value->muscle_mass, 1).' %';
                }

                if(!empty($value->metabolic_rate)){
                    $metabolic_rate = round($value->metabolic_rate);
                }

                if(!empty($value->biologic_age)){
                    $biologic_age = round($value->biologic_age);
                }

                if(!empty($value->body_age)){
                    $body_age = round($value->body_age);
                }

                if(!empty($value->description)){
                    $description = '<a herf="#" data-url="' . route('nutritionPanel.custom-dishes.viewDescription', ['id' => ev($value->id)]) . '" class="view-description cursor-pointer" title="View Description"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Description</div></a>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="custom_dish_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('nutritionPanel.custom-dishes.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "mobile_number"     => $mobile_number,
                    "age"               => $age,
                    "weight"            => $weight,
                    "height"            => $height,
                    "gender"            => $gender,
                    "bmi"               => $bmi,
                    "body_fat"          => $body_fat,
                    "visceral_fat"      => $visceral_fat,
                    "muscle_mass"       => $muscle_mass,
                    "metabolic_rate"    => $metabolic_rate,
                    "biologic_age"      => $biologic_age,
                    "body_age"          => $body_age,
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
     * Store Bmi Calculator.
     *
     * @return mixed
     *
     * @author Sandeep
     * @created 24 Jan 2023
     */
    public function store(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $bmiCalculator  = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Bmi Calculator
        try {

            // Convert height to meters
            $heightM    = $request->height / 100;
            $weight     = $request->weight;
            $age        = $request->age;
            $gender     = $request->gender;

            $bmi = $weight / ($heightM * $heightM);

            if ($gender == 1) {
                $bodyFat = (1.20 * $bmi) + (0.23 * $age) - 16.2;
            } else {
                $bodyFat = (1.20 * $bmi) + (0.23 * $age) - 5.4;
            }

            if ($gender == 1) {
              $muscleMass = (100 - $bodyFat - 10);
            } else {
              $muscleMass = (100 - $bodyFat - 46.0);
            }

            $visceralFat = ($bmi * 0.45 + $age * 0.15 + ($gender == 1 ? 1.5 : 0));

            if ($gender == 1) {
                $metabolicRate = 88.36 + (13.7 * $weight) + (4.8 * $heightM * 100) - (5.7 * $age);
            } else {
                $metabolicRate = 447.6 + (9.2 * $weight) + (3.1 * $heightM * 100) - (4.3 * $age);
            }

            $biologicAge = $age + (($bmi - 22) * 0.5) + (($bodyFat - 18) * 0.3) - (($muscleMass - 30) * 0.2);

            $bodyAge = $age + (($bmi - 22) * 0.6) + (($bodyFat - 20) * 0.4);

            // Set data
            $data = [
                'user_id'           => $request['user'],
                'name'              => $request['name'],
                'mobile_number'     => $request['mobile_number'],
                'age'               => $request['age'],
                'weight'            => $request['weight'],
                'height'            => $request['height'],
                'gender'            => $request['gender'],
                'bmi'               => $bmi,
                'body_fat'          => $bodyFat,
                'visceral_fat'      => $visceralFat,
                'muscle_mass'       => $muscleMass,
                'metabolic_rate'    => $metabolicRate,
                'biologic_age'      => $biologicAge,
                'body_age'          => $bodyAge,
                'created_by'        => $authUser->id,
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ];
            
            $bmiCalculator = BmiCalculator::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $bmiCalculator       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('BMI Calculator Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($bmiCalculator)) 
        {
            // Set notification
            $response = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Bmi Calculator']),
                '_type' => 'success',
            ];
            //-----------------

            return response()->json($response, 200);
        } 
        else 
        {
            // Set notification
            $response = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Bmi Calculator']),
                '_type' => 'error',
            ];
            //-----------------

            return response()->json($response, 200);
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
        $ids = $request['ids'];
        $bmiCalculator = BmiCalculator::whereIn('id', $ids)->delete();
        
        // Set response
        if ($bmiCalculator == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Bmi Calculator']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Bmi Calculator']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

}
