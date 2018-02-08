<?php

namespace App\Exceptions;

use App\Components\ApiResponse;
use App\Components\Errors;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception $exception
     * @return void
     * @throws Exception
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
     * @return ApiResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
//        return parent::render($request, $exception);
//        var_dump(get_class($exception));exit;
        switch ($request->getHttpHost()) {
            case config('global.domain.admin'):
                return $this->renderAdmin($request, $exception);
            case config('global.domain.web'):
                return $this->renderWeb($request, $exception);
            default:
                return parent::render($request, $exception);
        }
    }

    /**
     * 渲染前端异常
     * @param $request
     * @param Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function renderWeb($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return Response::view('web.404')->setStatusCode($exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }

    /**
     * 渲染后台异常
     * @param $request
     * @param Exception $exception
     * @return ApiResponse
     */
    private function renderAdmin($request, Exception $exception)
    {
        $code = $exception->getCode();
        if ($code == 0) {
            $code = -1;
        }

        if ($exception instanceof AuthenticationException) {
            return ApiResponse::fail(Errors::notLogin());
        }

        if ($exception instanceof HttpException) {
            return ApiResponse::fail("请求失败：HttpException[{$exception->getStatusCode()}]", $exception->getStatusCode());
        }

        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::fail(Errors::modelNotFound());
        }

        if ($exception instanceof ValidationException) {
            return ApiResponse::fail(current(current($exception->errors())), 422);
        }

        return ApiResponse::fail($exception->getMessage(), $code);
    }
}
