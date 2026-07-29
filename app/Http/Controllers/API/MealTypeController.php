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

use App\Models\MealType;
use Intervention\Image\Facades\Image;

class MealTypeController extends Controller
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
     * View Today Meal Type.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();
        $mealType = MealType::where('id', $userInfo['meal_type_id'])->select('id', 'name', 'image', 'description')->first();

        $mealDescription            = json_decode($mealType->description, true);
        $currentDay                 = Carbon::now()->isoWeekday();
        $mealType['description']    = $mealDescription[$currentDay] ?? [];

        $finalMeals = [];

        $dayMeals = $mealDescription[$currentDay] ?? [];

        foreach ($dayMeals as $mealName => $meal) {
            $finalMeals[] = [
                "title"       => $mealName,
                "time"        => $meal['time'] ?? null,
                "description" => $meal['description'] ?? null,
            ];
        }

        $mealType['description'] = $finalMeals;

        // Set response
        if(!empty($mealType)) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'meal type']),
                '_data'    => $mealType
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'meal type']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
