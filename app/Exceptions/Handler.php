<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     *
     * @throws Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response | mixed
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof Error) {
            return $this->error($e);
        }

        if ($e instanceof HttpException) {
            return $this->error($e);
        }

        if ($e instanceof ValidationException) {
            return $this->error($e);
        }

        return parent::render($request, $e);
    }

    /**
     * 自定义错误输出
     *
     * @param Error  $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function error($e)
    {
        $statusCode = ($e instanceof ValidationException) ? 500 : $e->getStatusCode();
        $message    = ($e instanceof ValidationException) ?
            current(current(array_values($e->errors()))) : $e->getMessage();

        $code = empty($e->getCode()) ? 100000 : $e->getCode();
        $data = empty($e->data) ? [] : $e->data;

        $response = [
            'id'      => md5(uniqid()),
            'code'    => $code,
            'status'  => $statusCode,
            'message' => $message,
            'error'   => 'ERROR',
            'data'    => $data
        ];

        Log::error('Response Error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));

        return response()->json($response, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }
}
