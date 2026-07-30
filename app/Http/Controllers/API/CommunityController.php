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

use App\Models\User;
use App\Models\Community;
use App\Models\CommunityImage;
use Intervention\Image\Facades\Image;

class CommunityController extends Controller
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
     * Add Community.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        // Store Community
        try {

            $community = Community::create([
                'user_id'   => $user->id,
                'message'   => $request['message'],
            ]);

            // Store into other image table
            if($request->hasFile('images')) {
                foreach ($request['images'] as $key => $img) {

                    $other_images = $this->uploadImage($request->file('images')[$key], config('constants.communities.image_path'));

                    if ($other_images['_status']) {
                        $other_image_name = $other_images['_data'];
                    
                        // Set data
                        $data = [
                            'community_id'      => $community['id'],
                            'image'             => $other_image_name,
                            'type'              => 1,
                        ];
                        //---------

                        CommunityImage::create($data);
                    }
                } 
            }
            //----------------------------
            
        } catch (\Exception $e) {
            \Log::error('Community add Error: ' . $e->getMessage());
            $community = null;
        }
        //-----------------------

        // Set response
        if (($community)) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_created', ['record' => 'community']),
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'community']),
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
