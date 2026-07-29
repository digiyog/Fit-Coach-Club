<?php

namespace App\Http\Controllers\AdminPanel\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Password;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin.guest');
    }

    /**
     * View forgot password.
     *
     * @summary custom forgot password page UI.
     * @author Sumit
     * @created 16 July 2019
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showLinkRequestForm()
    {
        return view('admin-panel.auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @author Sumit
     * @created 16 July 2019
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {

        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if(!empty($user)){
            if ($user->hasRole('Super Admin') == true || $user->hasRole('Sub Admin') == true || $user->hasRole('Agency') == true) {
                // $response = $this->broker()->sendResetLink(
                //     $request->only('email')
                // );

                // if ($response === Password::RESET_LINK_SENT) {
                    return back()->with('status', 'We have e-mailed your password reset link!');
                // }

                // return back()->withErrors(
                //     ['email' => trans($response)]
                // );
            } else {
                return back()->with('error', __('error_messages.access_denied') );
            }
        } else {
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            return back()->withErrors(
                ['email' => trans($response)]
            );
        }

    }

}