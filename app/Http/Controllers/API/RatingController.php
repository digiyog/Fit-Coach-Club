<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Add or Update Rating.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        // 1. Validate rating input: must be numeric and between 1.0 and 5.0
        $validator = Validator::make($request->all(), [
            'rating'  => 'required|numeric|min:1|max:5',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                '_status'  => false,
                '_message' => 'Please provide a valid rating',
            ], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                '_status'  => false,
                '_message' => 'Invalid or expired authentication token',
            ], 401);
        }

        try {
            // 2. Format rating as string-compatible decimal value (e.g. 4.0, 4.5, 5.0)
            $formattedRating = number_format((float)$request->rating, 1, '.', '');

            // 3. Check if user already submitted a rating to prevent duplicate entries
            $existingRating = Rating::where('user_id', $user->id)->first();

            if ($existingRating) {
                // Update existing rating
                $existingRating->update([
                    'rating'         => $formattedRating,
                    'message'        => $request->has('message') ? $request->message : $existingRating->message,
                    'rating_user_id' => $user->created_by ?? $existingRating->rating_user_id,
                ]);
            } else {
                // Create new rating
                Rating::create([
                    'user_id'        => $user->id,
                    'rating_user_id' => $user->created_by ?? null,
                    'rating'         => $formattedRating,
                    'message'        => $request->input('message'),
                ]);
            }

            return response()->json([
                '_status'  => true,
                '_message' => 'Rating submitted successfully',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Rating submission error: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                '_status'  => false,
                '_message' => 'Something went wrong',
            ], 500);
        }
    }
}
