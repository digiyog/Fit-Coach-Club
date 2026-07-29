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

use App\Models\Tip;
use Intervention\Image\Facades\Image;

class VideoController extends Controller
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
     * View Tip.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        $videos = Tip::select('id', 'name', 'coach_name', 'link')
        ->where('created_by', $userInfo->created_by)
        ->where('status',1)
        ->orderBy('order', 'ASC')
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($videos[0])) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'video']),
                '_data'    => $videos
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'video']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
