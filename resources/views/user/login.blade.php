@extends('main')
@section('title', 'Đăng nhập')
@section('content')
<div class="container p-4 mt-5">
<div class="container-sm card p-4">
    <form action="" method="post" id="loginForm">
        @csrf

        <h1 class="text-center">Đăng nhập</h1>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="mb-3 mt-3">
            <label for="username" class="form-label">Tên người dùng:</label>
            <input type="text" class="form-control" 
                    id="username" placeholder="Nhập tên người dùng" name="username">
            <div class="invalid-feedback" id="usernameError"></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control" 
                    id="password" placeholder="Nhập mật khẩu" name="password">
            <div class="invalid-feedback" id="passwordError"></div>
        </div>


        <button type="submit" class="btn btn-success w-100" name="login">Đăng nhập</button>

        <div class="mt-3 text-center">
            Chưa có tài khoản? 
            <a href="">Đăng ký ngay</a>
        </div>
    </form>
</div>
</div>

<script src="{{ @asset('js/login.js') }}"></script>

@endsection