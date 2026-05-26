<?php

namespace App\Http\Controllers;

use App\Models\Fiction;
use App\Models\Series;
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

        $blocked_user = User::whereUsername($credentials['username'])->first();
        if($blocked_user->blocked_until && $blocked_user->blocked_until->isFuture())
            return back()->with('error', 'Tài khoản này bị chặn cho tới ' . $blocked_user->blocked_until)
                        ->withInput();


        // 2. Chỉ dùng 1 lệnh IF duy nhất để kiểm tra so khớp tài khoản trong DB
        // Sử dụng guard 'web' đã cấu hình cho User thường
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            
            // Đăng nhập thành công -> Làm mới Session ID để bảo mật
            $request->session()->regenerate();

            // Chuyển hướng người dùng về trang Profile hoặc trang trước đó họ đang xem dở
            return redirect()->intended(route('user.profile'))->with('success', 'Đăng nhập thành công');
        }

        // 3. Nếu sai tài khoản/mật khẩu -> Ném thông báo lỗi chung về Session
        return back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác.')->withInput();
    }


    // =========================================================
    // Đổi thông tin
    // Route: GET /author/change_info
    // =========================================================
    public function change_info()
    {
        return view('user.change_password');
    }

    // =========================================================
    // Xác nhận đổi thông tin
    // Route: PUT /author/change_info
    // =========================================================    
    public function change_info_attempt(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email|max:255',
            'new_password' => 'required|string|min:6'
        ], [
            // Custom thông báo lỗi bằng tiếng Việt nếu muốn
            'new_password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        $user = Auth::guard('web')->user();

        $exist_email = User::whereEmail(trim($request->email))->where('id', '!=', $user->id)->exists();
        if($exist_email)
            return back()->with('error', 'Email này đã được người khác đăng ký');

        $user->email = trim($request->email);
        $user->password = Hash::make(trim($request->new_password));
        if($user->save())
            return redirect()->route('user.profile')->with('success', 'Thay đổi thông tin thành công');

        return redirect()->route('user.profile')->with('error', 'Thay đổi thông tin thất bại');
    }


    // =========================================================
    // Xóa tài khoản
    // Route: GET /author/delete_account
    // =========================================================
    public function delete_account()
    {
        return view('user.delete_account');
    }

    public function delete_account_attempt(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ], [
            // Custom thông báo lỗi bằng tiếng Việt nếu muốn
            'password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        $user = Auth::guard('web')->user();

        if($user->email !== trim($request->email))
            return back()->with('error', 'Email không chính xác');
        else if(!Hash::check(trim($request->password), $user->password))
            return back()->with('error', 'Mật khẩu không chính xác');

        if($user->delete())
        {
            Series::where('user_id', $user->id)->delete();
            Fiction::where('user_id', $user->id)->delete();

            Auth::guard('web')->logout();

            // Xóa session và tạo CSRF token mới
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('user.login')->with('error', 'Đã xóa tài khoản');
        }

        return back()->with('error', 'Xóa tài khoản thất bại');
    }


    // =========================================================
    // Đăng xuất
    // Route: POST /author/logout
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