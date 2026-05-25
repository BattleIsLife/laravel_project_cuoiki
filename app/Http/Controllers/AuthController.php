<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('user.register');
    }

    // =========================================================
    // Xử lý đăng ký
    // Route: POST /register
    // =========================================================
    public function register(Request $request)
    {
        // 1. Xác thực dữ liệu đầu vào (Validation)
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6'
        ], [
            // Custom thông báo lỗi bằng tiếng Việt nếu muốn
            'username.unique' => 'Tên người dùng này đã tồn tại.',
            'email.unique'    => 'Địa chỉ email này đã được đăng ký.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        // 2. Tạo User mới vào Database (Mã UUID và mật khẩu băm sẽ tự xử lý)
        User::create([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Luôn băm mật khẩu bảo mật
        ]);
        return redirect()->route('user.login')->with('success', 'Đăng ký tài khoản thành công. Vui lòng đăng nhập');
    }

    // =========================================================
    // Hiển thị form đăng nhập
    // Route: GET /login
    // View : user.auth.login
    // =========================================================
    public function showLogin()
    {
        return view('user.login');
    }

    // =========================================================
    // Xử lý đăng nhập
    // Route: POST /login
    // =========================================================
    public function login(Request $request)
    {
        // 1. Tự động kiểm tra trống trường. Nếu lỗi, Laravel tự "quay xe" về form cũ
        $credentials = $request->validate([
            'username'    => 'required|string|min:3|max:50',
            'password' => 'required|string|min:6|max:50',
        ], 
        [
            'username.required' => 'Vui lòng nhập username',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Sử dụng withTrashed() để ép Laravel quét qua cả các bản ghi có deleted_at
        $user = User::withTrashed()->where('username', $credentials['username'])->first();

        if ($user && $user->trashed()) {
            // Nếu tìm thấy tài khoản nhưng tài khoản này đã dính deleted_at (trashed)
            return back()->with('error', 'Tài khoản này đã bị xóa.')
                        ->withInput();
        }

        // 2. Chỉ dùng 1 lệnh IF duy nhất để kiểm tra so khớp tài khoản trong DB
        // Sử dụng guard 'web' đã cấu hình cho User thường
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            
            // Đăng nhập thành công -> Làm mới Session ID để bảo mật
            $request->session()->regenerate();

            // Chuyển hướng người dùng về trang Profile hoặc trang trước đó họ đang xem dở
            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công');
        }

        // 3. Nếu sai tài khoản/mật khẩu -> Ném thông báo lỗi chung về Session
        return back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác.')->withInput();
    }

    // =========================================================
    // Đăng xuất
    // Route: POST /logout
    // =========================================================
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        // Xóa session và tạo CSRF token mới
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('user.login')
            ->with('success', 'Bạn đã đăng xuất thành công.');
    }
}