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

use App\Models\CustomDish;
use Intervention\Image\Facades\Image;

class RecipeController extends Controller
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
     * View Recipes.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        $recipes = CustomDish::select('id', 'dish_type_id', 'name', 'image')
        ->with('dish_type', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'name');
        })
        ->where('created_by', $userInfo->created_by)
        ->where('status',1)
        ->orderBy('order', 'ASC')
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($recipes[0])) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'recipe']),
                '_data'    => $recipes
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'recipe']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * View Recipe Details.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function details(Request $request)
    {
        $userInfo = Auth::user();

        $recipe = CustomDish::where('status',1)
        ->with('dish_type', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'name');
        })
        ->where('id',$request['recipe_id'])
        ->first(); 

        // Set response
        if (!empty($recipe)) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'recipe details']),
                '_data'    => $recipe
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'recipe details']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

}
