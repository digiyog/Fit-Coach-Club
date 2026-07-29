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

use Intervention\Image\Facades\Image;

class BMICalculatorController extends Controller
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
     * View BMI Calculator.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        // Basic inputs
        $heightCm = $userInfo->height;           // in cm
        $heightM  = $heightCm / 100;             // convert to meters
        $weight   = $userInfo->current_weight;   // kg
        $age      = $userInfo->age;
        $gender   = $userInfo->gender;           // 1 = Male, 2 = Female

        // BMI
        $bmi = round($weight / ($heightM * $heightM), 2);

        // Body Fat %
        if ($gender == 1) {
            $bodyFat = (1.20 * $bmi) + (0.23 * $age) - 16.2;
        } else {
            $bodyFat = (1.20 * $bmi) + (0.23 * $age) - 5.4;
        }
        $bodyFat = round($bodyFat, 2);

        // Muscle Mass %
        if ($gender == 1) {
            $muscleMass = 100 - $bodyFat - 10;
        } else {
            $muscleMass = 100 - $bodyFat - 46;
        }
        $muscleMass = round($muscleMass, 2);

        // Skeletal Muscle %
        $skeletalMuscle = round($muscleMass * 0.85, 2);

        // Bone Mass (kg)
        $boneMass = round($weight * 0.04, 2);

        // Hydration %
        $hydration = round(100 - $bodyFat - 5, 2);

        // Visceral Fat
        $visceralFat = round(($bmi * 0.45) + ($age * 0.15) + ($gender == 1 ? 1.5 : 0), 2);

        // Protein %
        $protein = round($muscleMass * 0.2, 2);

        // Subcutaneous Fat %
        $subcutaneousFat = round($bodyFat * 0.8, 2);

        // BMR (Basal Metabolic Rate)
        if ($gender == 1) {
            $bmr = 88.36 + (13.7 * $weight) + (4.8 * $heightCm) - (5.7 * $age);
        } else {
            $bmr = 447.6 + (9.2 * $weight) + (3.1 * $heightCm) - (4.3 * $age);
        }
        $bmr = round($bmr, 2);

        // Metabolic Age
        $metabolicAge = round(
            $age +
            (($bmi - 22) * 0.5) +
            (($bodyFat - 18) * 0.3) -
            (($muscleMass - 30) * 0.2),
            1
        );

        // Body Age
        $bodyAge = round(
            $age +
            (($bmi - 22) * 0.6) +
            (($bodyFat - 20) * 0.4),
            1
        );

        // Final Data
        $data = [
            'weight'              => $weight,
            'bmi'                 => $bmi,
            'body_fat'            => $bodyFat,
            'muscle_mass'         => $muscleMass,
            'skeletal_muscle'     => $skeletalMuscle,
            'bone_mass'           => $boneMass,
            'hydration'           => $hydration,
            'visceral_fat'        => $visceralFat,
            'protein'             => $protein,
            'subcutaneous_fat'    => $subcutaneousFat,
            'metabolic_age'       => $metabolicAge,
            'body_age'            => $bodyAge,
            'BMR'                 => $bmr,
        ];

        $response = [
            '_status'  => true,
            '_message' => __('messages.record_found', ['record' => 'BMI calculator']),
            '_data'    => $data
        ];

        return response()->json($response, 200);
    }
}
