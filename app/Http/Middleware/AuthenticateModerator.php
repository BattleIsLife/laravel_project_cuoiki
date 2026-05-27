<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateModerator
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu moderator CHƯA đăng nhập thông qua guard 'moderators'
        if(!Auth::guard('moderator')->check())
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập');
        return $next($request);
    }
}
