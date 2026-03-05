<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckNoAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma','no-cache');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        if(!(Auth::user()))
        {
            $prefix = explode('/', $request->route()->getPrefix());
            $prefix = (empty($prefix[0]) ? $prefix[1] ?? null : $prefix[0] ?? null);
            
            if(!Route::currentRouteName() == 'adminPanel.login'){
                return redirect()->route('adminPanel.login');
            }
        }
        
        return $response;
    }
}
