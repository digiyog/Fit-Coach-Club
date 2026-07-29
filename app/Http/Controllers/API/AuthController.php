<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Traits\UploadImage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    use UploadImage;

    public function login(Request $request)
    {
        // Check user
        $user = User::where('email', $request->email)->where('role_type', 'user')->first();

        if (!$user) {
            return response()->json([
                '_status'  => false,
                '_message' => 'Invalid email address.',
            ], 200);
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                '_status'  => false,
                '_message' => 'Incorrect password.',
            ], 200);
        }

        if($user){ 
            if($user->status == 0){
                // Set response
                $response = [
                    '_status' => false,
                    '_message' => __('messages.account_inactive'),
                ];
                //-------------
                return response()->json($response, 200);
            }
        }

        // Create Token
        $token = $user->createToken('api_token')->plainTextToken;

        // Update login time
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return response()->json([
            '_status'  => true,
            '_message' => 'Login successful.',
            '_data'    => [
                'access_token' => $token,
                'user_data'    => $user,
            ],
        ], 200);
    }
}