@extends('user.profile')
@section('user_profile_component')
<div class="text-center">
    <h2>Thông tin tài khoản</h2>
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
</div>
<div class="row">
    <div class="col-sm-6">
        <p>Tên tài khoản: <i>{{ auth()->guard('web')->user()->username }}</i></p>
    </div>

    <div class="col-sm-6">
        <p>Email: <i>{{ auth()->guard('web')->user()->email }}</i></p>
    </div>
    <p><a href=">">Đổi mật khẩu</a></p>
</div>

@endsection