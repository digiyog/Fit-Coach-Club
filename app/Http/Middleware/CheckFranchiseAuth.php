<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFranchiseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma','no-cache');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        if(!(Auth::user()))
        {
            $user = Auth::user();

            if($user['role_type'] == 'super-admin'){
                return redirect()->route('adminPanel.login');
            } else {
                return redirect()->route('nutritionPanel.login');
            }
        }
        else{

            $user = Auth::user();

            if($user['role_type'] == 'super-admin'){
                return redirect()->route('adminPanel.login');
            } else {
                return $response;
            }
            // if($request->route()->action['as'] == 'adminPanel.dashboard' && (Auth::user()->role_name == config('constants.users.roles.SUPER_ADMIN.type') || Auth::user()->role_name == config('constants.users.roles.SUB_ADMIN.type'))){
            //     return redirect()->route('adminPanel.profile');
            // }
        }
        return $response;
    }
}
