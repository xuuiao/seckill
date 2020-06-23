<?php

use App\Exceptions\Error;
use Illuminate\Support\Facades\Log;

/**
 * 响应成功的结果
 */
if (! function_exists('success')) {
    function success(array $data = [], $httpCode = 200)
    {
        return response()->json([
            'code'       => 200,
            'message'    => 'success',
            'data'       => !empty($data) ? $data : (object)$data
        ], $httpCode);
    }
}

/**
 * 服务器发生了错误
 */
if (! function_exists('error')) {
    function error($code, $args = [],
                   $statusCode = 500,
                   \Exception $previous = null,
                   array $headers = [],
                   array $data = [])
    {
        $message = empty(config('code.'.$code)) ? '未知的错误' : config('code.'.$code);

        if (!empty($args)) {
            $message = vsprintf($message, $args);
        }

        throw new Error($code, $message, $statusCode, $previous, $headers, $data);
    }
}

/**
 * 提示性错误
 */
if (! function_exists('notice')) {
    function notice($code, $args = [], $data = [], $statusCode = 200, $previous = null, $headers = [])
    {
        $message = empty(config('code.'.$code)) ? '未知的错误' : config('code.'.$code);

        if (!empty($args)) {
            $message = vsprintf($message, $args);
        }

        throw new Error($code, $message, $statusCode, $previous, $headers, $data);
    }
}
