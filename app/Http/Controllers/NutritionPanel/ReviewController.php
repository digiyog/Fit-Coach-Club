<?php
namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Rating;

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

        $summary = Rating::getReviewsSummary();

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['users'] = $users;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['summary'] = $summary;
        
        return view('nutrition-panel.reviews.index')->with($this->viewData);
    }

    /**
     * Get Reviews list.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Mukesh
     * @created_at 23 Jan 2024
     */
    public function getReviews(Request $request, $id = false)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $order = $request->get('order');
        $sort = (!empty($order) && is_array($order)) ? $order[0] : null;

        $searchVal = $request->get('search');
        $search = is_array($searchVal) ? ($searchVal['value'] ?? null) : null;
        if (empty($search) && !empty($request->search_query)) {
            $search = $request->search_query;
        }
        
        // Filter Parameters
        $filter = array(
            "user_id" => $request->user_id,
            "rating_filter" => $request->rating_filter,
            "message_filter" => $request->message_filter,
            "filter_date_range" => $request->filter_date_range,
        );
        
        // Getting Reviews Records
        $records_count = Rating::GetReviews(null, null, $search, $filter, $sort);
        $records = Rating::GetReviews($limit, $start, $search, $filter, $sort);
        $summary = Rating::GetReviewsSummary($search, $filter);

        $arr_data = array();

        if(!empty($records) && count($records) > 0)
        {
            foreach($records as $key => $value)
            {   
                $name       = 'N/A';
                $ratingHtml = '';
                $message    = 'N/A';
                $created_at = 'N/A';

                if(!empty($value->name))
                {
                    $name = e($value->name);
                }

                $ratingNum = intval($value->rating ?? 0);
                if ($ratingNum >= 1) {
                    $starsHtml = '';
                    for ($s = 1; $s <= 5; $s++) {
                        if ($s <= $ratingNum) {
                            $starsHtml .= '<i class="fa fa-star star-filled"></i>';
                        } else {
                            $starsHtml .= '<i class="fa fa-star star-empty"></i>';
                        }
                    }
                    $ratingHtml = '<div class="rating-cell"><span class="rating-num">' . $ratingNum . '</span> <span class="stars-gold">' . $starsHtml . '</span></div>';
                } else {
                    $ratingHtml = '<span class="badge-unrated">Unrated</span>';
                }

                if(!empty($value->message))
                {
                    $message = e($value->message);
                }

                if(!empty($value->created_at))
                {
                    $created_at = Carbon::parse($value->created_at)->format('d-m-Y');
                }

                $checkbox = '<div class="custom-chk-wrap">'
                    . '<input type="checkbox" class="review-chk-native" id="chk_' . $value->id . '" value="' . $value->id . '">'
                    . '</div>';

                // Array Data
                $arr_data[] = array(
                    "checkbox"   => $checkbox,
                    "id"         => $value->id,
                    "name"       => '<span class="reviewer-name">' . $name . '</span>',
                    "rating"     => $ratingHtml,
                    "message"    => '<span class="review-message-text">' . $message . '</span>',
                    "created_at" => '<span class="review-date-text">' . $created_at . '</span>',
                );
            }
        }
        $totalRecords = $records_count;

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $arr_data,
            "summary"              => $summary
        );

        return response()->json($response);
    }

    /**
     * Destroy.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Mukesh
     * @created_at 23 Jan 2024
     */
    public function destroy(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json([
                '_status' => false,
                '_message' => 'Please select at least one review to delete.',
                '_type' => 'error'
            ], 200);
        }

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $reviews = Rating::whereIn('id', $ids)->delete();
        
        // Set response
        if ($reviews) 
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
        
        return response()->json($response, 200);
    }
}