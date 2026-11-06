<?php

namespace App\Http\Middleware;

use Closure;

class JsonMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures all requests to API endpoints enforce JSON format
     * and sanitizes malformed 'Bearer null' headers sent by Flutter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Enforce JSON acceptance
        $request->headers->set('Accept', 'application/json');

        // Safely strip malformed or empty Bearer tokens like "Bearer null"
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $authHeader));
            if ($token === '' || strtolower($token) === 'null' || strtolower($token) === 'undefined') {
                $request->headers->remove('Authorization');
                $request->server->set('HTTP_AUTHORIZATION', null);
            }
        }

        $response = $next($request);

        // Ensure JSON content type header
        if (method_exists($response, 'header')) {
            $response->header('Content-Type', 'application/json');
        } elseif (isset($response->headers)) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}
