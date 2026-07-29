<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Request;

class RolesPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard='web')
    {
        $authUser = auth()->user();

        if( Request::segment(2) == 'users' && Request::segment(3) == 'stylas'){
            if($authUser->hasPermissionTo('View Stylas') || $authUser->hasPermissionTo('Create Stylas') || $authUser->hasPermissionTo('Edit Stylas') || $authUser->hasPermissionTo('Delete Stylas')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'users' && Request::segment(3) == 'stylists'){
            if($authUser->hasPermissionTo('View Stylists') || $authUser->hasPermissionTo('Create Stylists') || $authUser->hasPermissionTo('Edit Stylists') || $authUser->hasPermissionTo('Delete Stylists')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'users' && Request::segment(3) == 'freelancers'){
            if($authUser->hasPermissionTo('View Freelancers') || $authUser->hasPermissionTo('Create Freelancers') || $authUser->hasPermissionTo('Edit Freelancers') || $authUser->hasPermissionTo('Delete Freelancers')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'users' && Request::segment(3) == 'salons'){
            if($authUser->hasPermissionTo('View Salons') || $authUser->hasPermissionTo('Create Salons') || $authUser->hasPermissionTo('Edit Salons') || $authUser->hasPermissionTo('Delete Salons')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'users' && Request::segment(3) == 'premium-salons'){
            if($authUser->hasPermissionTo('View Premium Salons') || $authUser->hasPermissionTo('Create Premium Salons') || $authUser->hasPermissionTo('Edit Premium Salons') || $authUser->hasPermissionTo('Delete Premium Salons')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'bookings'){
            if($authUser->hasPermissionTo('View Bookings') || $authUser->hasPermissionTo('Create Bookings') || $authUser->hasPermissionTo('Edit Bookings') || $authUser->hasPermissionTo('Delete Bookings')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'transactions'){
            if($authUser->hasPermissionTo('View Transactions') || $authUser->hasPermissionTo('Create Transactions') || $authUser->hasPermissionTo('Edit Transactions') || $authUser->hasPermissionTo('Delete Transactions')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'ratings-reviews'){
            if($authUser->hasPermissionTo('View Ratings') || $authUser->hasPermissionTo('Create Ratings') || $authUser->hasPermissionTo('Edit Ratings') || $authUser->hasPermissionTo('Delete Ratings')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'languages'){
            if($authUser->hasPermissionTo('View Languages') || $authUser->hasPermissionTo('Create Languages') || $authUser->hasPermissionTo('Edit Languages') || $authUser->hasPermissionTo('Delete Languages')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'services'){
            if($authUser->hasPermissionTo('View Services') || $authUser->hasPermissionTo('Create Services') || $authUser->hasPermissionTo('Edit Services') || $authUser->hasPermissionTo('Delete Services')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'service-details'){
            if($authUser->hasPermissionTo('View Service Details') || $authUser->hasPermissionTo('Create Service Details') || $authUser->hasPermissionTo('Edit Service Details') || $authUser->hasPermissionTo('Delete Service Details')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'sliders'){
            if($authUser->hasPermissionTo('View Sliders') || $authUser->hasPermissionTo('Create Sliders') || $authUser->hasPermissionTo('Edit Sliders') || $authUser->hasPermissionTo('Delete Sliders')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'cms'){
            if($authUser->hasPermissionTo('View CMS') || $authUser->hasPermissionTo('Create CMS') || $authUser->hasPermissionTo('Edit CMS') || $authUser->hasPermissionTo('Delete CMS')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'positions'){
            if($authUser->hasPermissionTo('View Positions') || $authUser->hasPermissionTo('Create Positions') || $authUser->hasPermissionTo('Edit Positions') || $authUser->hasPermissionTo('Delete Positions')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'cancel-reasons'){
            if($authUser->hasPermissionTo('View Cancel Reasons') || $authUser->hasPermissionTo('Create Cancel Reasons') || $authUser->hasPermissionTo('Edit Cancel Reasons') || $authUser->hasPermissionTo('Delete Cancel Reasons')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'countries'){
            if($authUser->hasPermissionTo('View Countries') || $authUser->hasPermissionTo('Create Countries') || $authUser->hasPermissionTo('Edit Countries') || $authUser->hasPermissionTo('Delete Countries')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'states'){
            if($authUser->hasPermissionTo('View States') || $authUser->hasPermissionTo('Create States') || $authUser->hasPermissionTo('Edit States') || $authUser->hasPermissionTo('Delete States')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'masters' && Request::segment(3) == 'cities'){
            if($authUser->hasPermissionTo('View Cities') || $authUser->hasPermissionTo('Create Cities') || $authUser->hasPermissionTo('Edit Cities') || $authUser->hasPermissionTo('Delete Cities')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'general-configurations'){
            if($authUser->hasPermissionTo('View General Configurations') || $authUser->hasPermissionTo('Create General Configurations') || $authUser->hasPermissionTo('Edit General Configurations') || $authUser->hasPermissionTo('Delete General Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'app-configurations'){
            if($authUser->hasPermissionTo('View App Configurations') || $authUser->hasPermissionTo('Create App Configurations') || $authUser->hasPermissionTo('Edit App Configurations') || $authUser->hasPermissionTo('Delete App Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'styla-configurations'){
            if($authUser->hasPermissionTo('View Styla Configurations') || $authUser->hasPermissionTo('Create Styla Configurations') || $authUser->hasPermissionTo('Edit Styla Configurations') || $authUser->hasPermissionTo('Delete Styla Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'stylist-configurations'){
            if($authUser->hasPermissionTo('View Stylist Configurations') || $authUser->hasPermissionTo('Create Stylist Configurations') || $authUser->hasPermissionTo('Edit Stylist Configurations') || $authUser->hasPermissionTo('Delete Stylist Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'freelancer-configurations'){
            if($authUser->hasPermissionTo('View Freelancer Configurations') || $authUser->hasPermissionTo('Create Freelancer Configurations') || $authUser->hasPermissionTo('Edit Freelancer Configurations') || $authUser->hasPermissionTo('Delete Freelancer Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'salon-configurations'){
            if($authUser->hasPermissionTo('View Salon Configurations') || $authUser->hasPermissionTo('Create Salon Configurations') || $authUser->hasPermissionTo('Edit Salon Configurations') || $authUser->hasPermissionTo('Delete Salon Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else if( Request::segment(2) == 'configurations' && Request::segment(3) == 'premium-salon-configurations'){
            if($authUser->hasPermissionTo('View Premium Salon Configurations') || $authUser->hasPermissionTo('Create Premium Salon Configurations') || $authUser->hasPermissionTo('Edit Premium Salon Configurations') || $authUser->hasPermissionTo('Delete Premium Salon Configurations')){
                return $next($request);
            } else {
                abort(403);
            }
        }
        else {
            return $next($request);
        }
        return $next($request);
    }
}
