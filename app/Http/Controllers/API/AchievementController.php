<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;

use App\Models\Achievement;
use Intervention\Image\Facades\Image;

class AchievementController extends Controller
{
    use UploadImage;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * View Achievement.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        if($userInfo['user_state'] == 'Online'){
            $type = 3;
        } else {
            $type = 2;
        }

        $achievements = Achievement::select('id', 'title', 'type', 'image');

        if($request['type'] != ''){
            $achievements = $achievements->where('type', $request['type']);
        }

        $achievements = $achievements->where('show_achievement','!=', $type)
        ->where('created_by', $userInfo->created_by)
        ->where('status',1)
        ->where('in_app_show',1)
        ->orderBy('order', 'ASC')
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($achievements[0])) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'achievement']),
                '_data'    => $achievements
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'achievement']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
