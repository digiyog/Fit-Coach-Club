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

use App\Models\Testimonial;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
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
     * View Testimonial.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        $testimonials = Testimonial::select('id', 'name', 'image', 'link')
        ->where('created_by', $userInfo->created_by)
        ->where('status',1)
        ->orderBy('order', 'ASC')
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($testimonials[0])) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'testimonial']),
                '_data'    => $testimonials
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'testimonial']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
