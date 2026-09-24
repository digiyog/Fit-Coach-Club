<?php
namespace App\Http\Controllers\NutritionPanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityImage;
use Storage;

class CommunityPhotoController extends Controller
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
     * View Community Photo.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author mukesh
     * @created_at 21 Jan 2024
     */
    public function index()
    {
        $authUser = auth()->user();

        $users = User::where('role_type','users')->select('id' ,DB::raw("If(users.mobile_number is null, users.name, CONCAT(users.name, ' (', users.mobile_number, ')')) as name"))
        ->get();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Community Photos' => '',
        ];

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
        $this->viewData['authUser'] = $authUser;
        $this->viewData['users'] = $users;
        
        return view('nutrition-panel.community-photos.index')->with($this->viewData);
    }

    /**
     * Get Community Photos list.
     *
     * @return response
     *
     * @author Mukesh
     * @created_at 21 Jan 2024
     */
    public function getCommunityPhotos(Request $request)
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
            "name" => $request->name,
            "mobile_number" => $request->mobile_number,
        );
        
        // Getting Community Photos Records
        $records_count = Community::GetCommunityPhotos(null, null, $search, $filter, $sort);
        $records = Community::GetCommunityPhotos($limit, $start, $search, $filter, $sort);

        $arr_data = array();
        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name = 'N/A';
                $message = 'N/A';
                $date_time = 'N/A';
                $view_photos = '0';

                // Preparing Data
                if(!empty($value->user)){
                    $userName = $value->user['name'];
                }

                if(!empty($value->message)){
                    $message = $value->message;
                }

                if(!empty($value->community_images_count))
                {
                    $view_photos = $value->community_images_count;
                }

                if(!empty($value->created_at))
                {
                    $date_time = date("d F Y",strtotime($value->created_at)).' <br> '.date("h:i A",strtotime($value->created_at));
                }
            
                $view_photos = '<a herf="#" data-url="' . route('nutritionPanel.community-photos.viewPhotos', ['id' => ev($value->id)]) . '" class="view-photos cursor-pointer" title="View Photos"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Photos</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"            => $value->id,
                    "name"          => $userName,
                    "message"       => $message,
                    "view_photos"   => $view_photos,
                    "date_time"     => $date_time,
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
     * View Photos.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function viewPhotos($id)
    {
        $auth_user = auth()->user();

        // Get Photos
        $photos = CommunityImage::where('community_id', dv($id))->get();

        // Send view data
        $this->viewData['photos'] = $photos;

        return view('nutrition-panel.community-photos.view-photos')->with($this->viewData);
    }

}