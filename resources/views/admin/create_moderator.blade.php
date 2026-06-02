@extends('main')
@section('title', 'Tạo tài khoản moderator')
@section('content')
<div class="container p-4 mt-5">
<div class="container-sm card p-4">
    <form action="{{ url('admin/register') }}" method="post" id="registerForm">
        @csrf

        <h1 class="text-center">Tạo tài khoản moderator</h1>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0" style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="mb-3 mt-3">
            <label for="username" class="form-label">Tên người dùng:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng">
            <div class="invalid-feedback" id="usernameError"></div>
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
            <div class="invalid-feedback" id="emailError"></div>
        </div>

        <div class="mb-3 mt-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
            <div class="invalid-feedback" id="passwordError"></div>
        </div>

        <div class="mb-3 mt-3">
            <label for="blocked_until" class="form-label">Vai trò</label>
            <select class="form-select" id="permission" name="permission">
                <option value="none" selected>Không có</option>
                <option value="user_moderator">Quản trị người dùng</option>
                <option value="post_moderator">Quản trị bài đăng</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100" id="submitBtn">Đăng ký</button>
    </form>
</div>
</div>

<script src="{{ @asset('js/register.js') }}"></script>

@endsection