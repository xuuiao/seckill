<?php

namespace App\Http\Middleware;

use Closure;

class SignMiddleware
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
        // 开发环境不校验签名
        if (config('app.env') === 'dev') {
            return $next($request);
        }

        $sign = $request->header('Auth-Sign');
        $client = $request->header('Auth-Client');
        $timestamp = $request->header('Auth-Timestamp');

        if (empty($sign)) {
            return error(100001, [], 401);
        }

        if (empty($client)) {
            return error(100002, [], 401);
        }

        if (empty($timestamp)) {
            return error(100003, [], 401);
        }

        $secret = config('client.'.$client);

        if (empty($secret)) {
            return error(100004, [], 401);
        }

        $verifySign = md5($client.$secret.$timestamp);
        $difference = time() - $timestamp;

        if ($difference > 3600 || $difference < 0) {
            return error(100005, [], 401);
        }

        if ($sign !== $verifySign) {
            return error(100006, [], 401);
        }

        return $next($request);
    }
}
