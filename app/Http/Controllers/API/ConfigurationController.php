<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Configuration;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
   
class ConfigurationController extends Controller
{

    /**
     * Index.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        // $configuration = null;

        // DB::beginTransaction();
        // try {  

        //     // Get Configurations
        //     $configuration = Configuration::select('id','config_name','config_value')->get();
        //     //-------------------

        //     // Server current date
        //     $currentDateTime = Carbon::now();
        //     $currentDate = $currentDateTime->toDateString();
        //     //--------------------

        //     DB::commit();
        // } catch (\Exception $e) {
        //     $errorMessage = $e->getMessage();
        //     DB::rollBack();
        // }

        // // Set response
        // if (($configuration)) {
            
            $bucket_base_url    = env('AWS_CloudFront_URL').'/';

            // // Transform into an object with modified keys
            // $configObject = $configuration->pluck('config_value', 'config_name')
            //     ->mapWithKeys(function ($value, $key) {
            //         $modifiedKey = str_replace('-', '_', $key); // Replace hyphens with underscores
            //         return [$modifiedKey => $value];
            //     })
            //     ->toArray();

            $response = [
                '_status' => true,
                '_message' => __('messages.record_found', ['record' => 'Configuration']),
                '_data' => [
                    'user_image_path'               => $bucket_base_url. config('constants.users.image_path'),
                    'weight_image_path'             => $bucket_base_url. config('constants.weights.image_path'),
                    'product_image_path'            => $bucket_base_url. config('constants.products.image_path'),
                    'achievement_image_path'        => $bucket_base_url. config('constants.achievements.image_path'),
                    'activity_image_path'           => $bucket_base_url. config('constants.activities.image_path'),
                    'recipe_image_path'             => $bucket_base_url. config('constants.custom-dishes.image_path'),
                    'testimonial_image_path'        => $bucket_base_url. config('constants.testimonials.image_path'),
                    'meal_type_image_path'          => $bucket_base_url. config('constants.meal-types.image_path'),
                ],
            ];
        // } else {
        //     $response = [
        //         '_status' => false,
        //         '_message' => __('messages.record_not_found', ['record' => 'Configuration']),
        //         '_data' => null,
        //     ];
        // }

        return response()->json($response, 200);

    }
}