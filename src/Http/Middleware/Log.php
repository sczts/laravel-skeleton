<?php

namespace Sczts\Skeleton\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log as Logger;

class Log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $start = $this->millisecond();
        $response = $next($request);
        $end = $this->millisecond();
        $diff = $end - $start;
        $route = $request->route();
        $param = [
            'method' => $request->method(),
            'time_diff' => $diff,
            'ip' => $request->getClientIp(),
            'parameters'  =>  $route->parameters,
            'input' =>  $request->all()
        ];
        Logger::channel('api_request')->info($route->uri,$param);
        return $response;
    }

    // 获取当前时间 毫秒
    public function millisecond()
    {
        $time = explode (" ", microtime());
        $time = $time[1]*1000 + ($time [0] * 1000);
        return ceil($time);
    }
}
