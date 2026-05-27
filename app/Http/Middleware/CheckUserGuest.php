<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra cả user và moderator để tránh việc moderator vẫn có thể đăng nhập user và ngược lại
        if(Auth::guard('web')->check())
            return redirect()->route('user.profile');

        if(Auth::guard('moderator')->check())
            return redirect()->route('admin.dashboard');
        return $next($request);
    }
}
