<?php
namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\NutritionLoginRequest;
use DB;
use Illuminate\Support\Facades\Mail;
use App;
use Auth;
use DateTime;
use App\Models\User;
use App\Http\Traits\UploadImage;
use Illuminate\Support\Facades\Hash;
use Http;

class LoginController extends Controller
{
    use UploadImage;

    /**
      Login Page
    **/
    public function login(Request $request)
    {
        $data = array(
            'pageTitle'             => 'Login | Nutrition Panel',
            'pageDescrption'        => 'Nutrition Panel',
        );

        return view('nutrition-auth.login')->with($data);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginUser(NutritionLoginRequest $request)
    {
        if (!app()->environment('local') && !in_array($request->getHost(), ['localhost', '127.0.0.1'])) {
            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]
            );

            if (!($response->json()['success'] ?? false)) {
                return back()->withErrors(['captcha' => 'Captcha verification failed']);
            }
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::NUTRITION_HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('nutritionPanel.login');
    }
}
