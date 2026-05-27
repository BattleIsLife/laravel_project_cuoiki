<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Moderator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorAuthController extends Controller
{
    // =========================================================
    // Hiển thị form đăng nhập moderator
    // Route: GET /admin/login
    // View : admin/login
    // =========================================================
    public function showLogin()
    {
        return view('admin.login');
    }

    // =========================================================
    // Xử lý đăng nhập moderator
    // Route: POST /admin/login
    // =========================================================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|min:3|max:50',
            'password' => 'required|string|min:6|max:50',
        ], [
            'username.required' => 'Vui lòng nhập username.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Kiểm tra tài khoản có bị xóa không
        $moderator = Moderator::withTrashed()
            ->where('username', $credentials['username'])
            ->first();

        if ($moderator && $moderator->trashed()) {
            return back()->with('error', 'Tài khoản này đã bị xóa.')->withInput();
        }

        // Đăng nhập bằng guard 'moderator'
        if (Auth::guard('moderator')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công.');
        }

        return back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác.')->withInput();
    }

    // =========================================================
    // Đăng xuất moderator
    // Route: POST /admin/logout
    // =========================================================
    public function logout(Request $request)
    {
        Auth::guard('moderator')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}