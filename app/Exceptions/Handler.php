<?php

namespace App\Exceptions;

use App\Components\ApiResponse;
use App\Components\Errors;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return ApiResponse
     */
    public function render($request, Exception $exception)
    {
        $code = $exception->getCode();
        if ($code == 0) {
            $code = -1;
        }

        if ($exception instanceof AuthenticationException) {
            return ApiResponse::fail(Errors::notLogin());
        }

        if ($exception instanceof HttpException) {
//            return parent::render($request, $exception);
            return ApiResponse::fail("请求失败：HttpException[{$exception->getStatusCode()}]", $exception->getStatusCode());
        }

        return ApiResponse::fail($exception->getMessage(), $code);
    }
}
