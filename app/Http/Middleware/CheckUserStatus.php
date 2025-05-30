<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->status == 1 && !$request->is('admin/auth/login', 'admin', 'admin/*', 'logout')) {
            return abort(403, '你的账户被禁用,请联系xitongtongzhi@ai-space.net');
        }
        return $next($request);
    }
}
