<?php
namespace App\Http\Controllers\NutritionPanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Models\Rating;
use Storage;

class ReviewController extends Controller
{
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
     * View Review.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author mukesh
     * @created_at 23 Jan 2024
     */
    public function reviews()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Reviews' => '',
        ];

        $users = User::where('role_type','user')->select('id' ,DB::raw("If(users.mobile_number is null, users.name, CONCAT(users.name, ' (', users.mobile_number, ')')) as name"))->get();

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Filter Button
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
        $this->viewData['users'] = $users;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.reviews.index')->with($this->viewData);
    }

    /**
     * Get Reviews list.
     *
     * @return response
     *
     * @author Mukesh
     * @created_at 23 Jan 2024
     */
    public function getReviews(Request $request , $id=false)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
            "user_id" => $request->user_id,
        );
        
        // Getting Reviews Records
        $records_count = Rating::GetReviews(null, null, $search, $filter, $sort);
        $records = Rating::GetReviews($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {   
                $name       = 'N/A';
                $rating     = 'N/A';
                $message    = 'N/A';
                $created_at = 'N/A';
                $action     = '';

                if(!empty($value->name))
                {
                    $name = $value->name;
                }

                if(!empty($value->rating))
                {
                    $rating = $value->rating;
                }

                if(!empty($value->message))
                {
                    $message = $value->message;
                }

                if(!empty($value->created_at))
                {
                    $created_at = date("d-m-Y", strtotime($value->created_at));
                }

                // Array Data
                $arr_data[] = array(
                    "id" => $value->id,
                    "name" => $name,
                    "rating" => $rating,
                    "message" => $message,
                    "created_at" => $created_at,
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );

        return json_encode($response);
    }

    /**
     * Destroy.
     *
     * @return boolean
     *
     * @author Mukesh
     * @created_at 23 Jan 2024
     */
    public function destroy(Request $request)
    {
        $ids = $request['ids'];
        $reviews = Rating::whereIn('id', $ids)->delete();
        
        // Set response
        if ($reviews == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Review']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Review']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }
}