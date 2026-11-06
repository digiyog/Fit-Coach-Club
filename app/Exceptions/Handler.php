<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        // 1. Force consistent JSON error handling across all API routes & JSON requests
        if ($request->is('api/*') || $request->expectsJson() || $request->isJson()) {
            return $this->handleApiException($request, $exception);
        }

        // 2. Existing web / admin-panel exception handling
        if ($this->isHttpException($exception)) {
            if ($request->is('admin-panel/*') && !$request->ajax()) {
                return response()->view('errors.' . $exception->getStatusCode(), [], $exception->getStatusCode());
            } elseif ($request->is('admin-panel/*') && $request->ajax()) {
                return response()->json([
                    '_status'  => false,
                    '_message' => $exception->getMessage(),
                    '_type'    => 'error',
                ], $exception->getStatusCode());
            } else {
                if ($exception->getStatusCode() == 404) {
                    return response()->view('errors.404', [], 404);
                }
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Format all API exceptions into the mandatory standardized JSON response format.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException($request, Throwable $exception)
    {
        $statusCode = 500;
        $message    = 'Something went wrong';

        if ($exception instanceof AuthenticationException) {
            $statusCode = 401;
            $message    = 'Invalid or expired authentication token';
        } elseif ($exception instanceof ValidationException) {
            $statusCode = 422;
            $message    = $exception->validator->errors()->first() ?: 'The given data was invalid.';
        } elseif ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            $statusCode = 403;
            $message    = 'Access forbidden';
        } elseif ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message    = 'Resource not found';
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $message    = 'Method not allowed';
        } elseif ($exception instanceof BadRequestHttpException) {
            $statusCode = 400;
            $message    = $exception->getMessage() ?: 'Bad request';
        } elseif ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();

            switch ($statusCode) {
                case 400:
                    $message = $exception->getMessage() ?: 'Bad request';
                    break;
                case 401:
                    $message = 'Invalid or expired authentication token';
                    break;
                case 403:
                    $message = 'Access forbidden';
                    break;
                case 404:
                    $message = 'Resource not found';
                    break;
                case 405:
                    $message = 'Method not allowed';
                    break;
                case 422:
                    $message = $exception->getMessage() ?: 'Validation error';
                    break;
                case 500:
                default:
                    $message = ($statusCode >= 500) ? 'Internal server error' : ($exception->getMessage() ?: 'Something went wrong');
                    break;
            }
        } else {
            // Unhandled 500 Server Error: Never leak SQL queries, traces or paths to client
            $statusCode = 500;
            $message    = 'Something went wrong';

            // Technical details securely stored in server logs
            Log::error('API Internal Server Error [500]: ' . $exception->getMessage(), [
                'exception_class' => get_class($exception),
                'file'            => $exception->getFile(),
                'line'            => $exception->getLine(),
                'url'             => $request->fullUrl(),
                'method'          => $request->method(),
                'trace'           => $exception->getTraceAsString(),
            ]);
        }

        return response()->json([
            '_status'  => false,
            '_message' => $message,
        ], $statusCode, [
            'Content-Type' => 'application/json',
        ], JSON_UNESCAPED_SLASHES);
    }
}
