<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu user CHƯA đăng nhập thông qua guard 'web'
        if(!Auth::guard('web')->check())
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập');
        return $next($request);
    }
}
