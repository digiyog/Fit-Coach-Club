<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if(env('API_LOG') == true) {

            if(!empty($request->user())) {
                $userId = $request->user()->id;
            } else {
                $userId = null;
            }

            $logEntry = new ApiLog();
            $logEntry->url = $request->fullUrl();
            $logEntry->method = $request->method();
            $logEntry->user_id = $userId;
            $logEntry->request_header = json_encode($request->header(), true);
            $logEntry->request_body = json_encode($request->all(), true);
            $logEntry->response_body = json_encode($response->getContent(), true);
            $logEntry->status_code = $response->getStatusCode();
            $logEntry->device_type = $request->header('device-type') ?? null;
            $logEntry->device_name = $request->header('device-name') ?? null;
            $logEntry->device_version = $request->header('device-version') ?? null;
            $logEntry->request_ip = $request->ip();
            $logEntry->save();

        }
    }
}
