<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {

            if (request()->is('admin-panel/*') && !request()->ajax()) {
                return response()->view('errors.' . $exception->getStatusCode(), [], $exception->getStatusCode());
            }
            else if (request()->is('admin-panel/*') && request()->ajax()) {
                // Set response
                $response = [
                    '_status' => false,
                    '_message' => $exception->getMessage(),
                    '_type' => 'error',
                ];
                //----------

                return response()->json($response, $exception->getStatusCode());
            }
            else
            {
                if ($exception->getStatusCode() == 404) {
                    return response()->view('errors.' . '404', [], 404);
                }
            }
        }

        return parent::render($request, $exception);
    }
}
