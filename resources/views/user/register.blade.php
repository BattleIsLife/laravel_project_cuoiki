@extends('main')
@section('title', 'Đăng ký')
@section('content')
<div class="container p-4 mt-5">
<div class="container-sm card p-4">
    <form action="{{ url('/register') }}" method="post" id="registerForm">
        @csrf

        <h1 class="text-center">Đăng ký</h1>
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
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng">
            <div class="invalid-feedback" id="usernameError"></div>
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
            <div class="invalid-feedback" id="emailError"></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
            <div class="invalid-feedback" id="passwordError"></div>
        </div>

        <button type="submit" class="btn btn-success w-100" id="submitBtn">Đăng ký</button>
    </form>
</div>
</div>

<script src="{{ @asset('js/register.js') }}"></script>

@endsection