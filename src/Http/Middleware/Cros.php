<?php

namespace Sczts\Skeleton\Http\Middleware;

use Closure;

class Cros
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 设置允许访问的域地址
        $domains = config('skeleton.cros.domains');
        if (isset($request->server()['HTTP_ORIGIN'])) {
            $origin = $request->server()['HTTP_ORIGIN'];
            if (in_array($origin, $domains)) {
                header("Access-Control-Allow-Origin: ".$origin);
                header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
                header("Access-Control-Allow-Headers: X-Requested-With,Content-Type,Origin,Authorization,SystemInfo,JWTRefreshToken");
                header("Access-Control-Allow-Credentials : true");
            }
        }
        $response = $next($request);
        return $response;
    }
}
