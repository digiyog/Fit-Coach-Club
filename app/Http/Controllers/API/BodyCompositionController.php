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

class BodyCompositionController extends Controller
{
    use UploadImage;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $heightCm = $user->height;
        $heightM  = $heightCm / 100;
        $weight   = $user->current_weight;
        $age      = $user->age;
        $gender   = $user->gender; // 1 = Male, 2 = Female

        // BMI
        $bmi = round($weight / ($heightM * $heightM), 2);

        // Body Fat %
        $bodyFat = ($gender == 1)
            ? (1.20 * $bmi) + (0.23 * $age) - 16.2
            : (1.20 * $bmi) + (0.23 * $age) - 5.4;
        $bodyFat = round($bodyFat, 2);

        // Muscle Mass %
        $muscleMass = ($gender == 1)
            ? 100 - $bodyFat - 10
            : 100 - $bodyFat - 46;
        $muscleMass = round($muscleMass, 2);

        $skeletalMuscle   = round($muscleMass * 0.85, 2);
        $boneMass         = round($weight * 0.04, 2);
        $hydration        = round(100 - $bodyFat - 5, 2);
        $visceralFat      = round(($bmi * 0.45) + ($age * 0.15) + ($gender == 1 ? 1.5 : 0), 2);
        $protein          = round($muscleMass * 0.2, 2);
        $subcutaneousFat  = round($bodyFat * 0.8, 2);
        $metabolicAge     = round($age + (($bmi - 22) * 0.5) + (($bodyFat - 18) * 0.3), 1);

        // Helper
        function metric($name, $value, $min, $max)
        {
            $percentage = (($value - $min) / ($max - $min)) * 100;
            $percentage = max(0, min(100, round($percentage, 1)));

            if ($value < $min) {
                $status = 'low';
            } elseif ($value > $max) {
                $status = 'high';
            } else {
                $status = 'normal';
            }

            return [
                'name'       => $name,
                'value'      => round($value, 2),
                'min'        => $min,
                'max'        => $max,
                'percentage' => $percentage,
                'status'     => $status
            ];
        }

        $data = [
            metric('Weight', $weight, 40, 150),
            metric('Body Fat', $bodyFat, 8, 25),
            metric('Muscle Mass', $muscleMass, 30, 60),
            metric('Skeletal Muscle', $skeletalMuscle, 25, 45),
            metric('Bone Mass', $boneMass, 2, 5),
            metric('Hydration', $hydration, 45, 65),
            metric('Visceral Fat', $visceralFat, 1, 12),
            metric('Protein', $protein, 16, 20),
            metric('Subcutaneous Fat', $subcutaneousFat, 10, 30),
            metric('Metabolic Age', $metabolicAge, 18, 80),
        ];

        // Overall Score (average of percentages)
        $overallScore = round(
            collect($data)->avg('percentage'),
            1
        );

        return response()->json([
            '_status'       => true,
            '_message'      => 'Body composition calculated successfully',
            'overallScore'  => $overallScore,
            '_data'         => $data
        ], 200);
    }


}
