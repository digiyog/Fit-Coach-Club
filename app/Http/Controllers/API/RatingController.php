<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Rating;
use App\Http\Traits\UploadImage;
use DB;

class RatingController extends Controller
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
     * Add Rating.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        try {

            // 🔍 Check if rating already exists
            $alreadyRated = Rating::where('user_id', $user->id)
                ->where('rating_user_id', $user->created_by)
                ->exists();

            if ($alreadyRated) {
                return response()->json([
                    '_status'  => false,
                    '_message' => 'You have already submitted your rating. You cannot rate again.',
                ], 200);
            }

            // ✅ Create rating
            $addRating = Rating::create([
                'user_id'         => $user->id,
                'rating_user_id'  => $user->created_by,
                'rating'          => $request->rating,
                'message'         => $request['message']
            ]);

            return response()->json([
                '_status'  => true,
                '_message' => 'Rating has been submitted successfully.',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Rating submit Error: ' . $e->getMessage());

            return response()->json([
                '_status'  => false,
                '_message' => 'Unable to submit rating. Please try again later.',
            ], 200);
        }
    }
}
