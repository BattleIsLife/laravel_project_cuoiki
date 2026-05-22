@extends('main')
@section('content')
<div class="container p-4 mt-5">
<div class="container-sm card p-4">
    <form action="" method="post" id="deleteForm">
        @csrf

        <div class="text-center">
            <h1>Xóa tài khoản</h1>
            <h4>{{ session()->get('username') }}, bạn đang thực hiện xóa tài khoản. Hãy nghĩ thật kỹ trước khi tiếp tục.</h4>
        </div>

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <div class="mb-3 mt-3">
            <label for="username" class="form-label">Tên người dùng:</label>
            <input type="hidden" class="form-control" readonly
                    id="username" name="username" value="{{ session()->get('username') }}">
            <input type="text" class="form-control" disabled
                    id="username" name="username" readonly value="{{ session()->get('username') }}">
        </div>

        <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
                <div class="invalid-feedback" id="emailError"></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control" 
                    id="password" placeholder="Nhập mật khẩu" name="password">
            <div class="invalid-feedback" id="passwordError"></div>
        </div>

        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="agree" onchange="toggleDelete()">
            Tôi hiểu rằng mọi dữ liệu của tôi sẽ bị xóa sạch và quá trình này sẽ không thể đảo ngược!!
        </label>

        <button type="submit" class="btn btn-success w-100 mt-3 disabled" id="deleteAccount" name="login">Xác nhân xóa tài khoản</button>
    </form>
</div>
</div>

<script src={{ @asset('js/delete_account.js') }}></script>
@endsection