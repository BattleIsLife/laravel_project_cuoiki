@extends('admin.profile')
@section('moderator_profile_component')
@php
    // Tính toán quyền hạn
    $moderator = auth()->guard('moderator')->user();
    $permission = $moderator->permission;
    $permission_name = "";
    $permission_level = 0;
    switch ($permission) {
        case 'admin':
            $permission_name = 'Admin';
            break;
        
        case 'user_moderator':
            $permission_name = 'Quản trị người dùng';
            break;

        case 'post_moderator':
            $permission_name = 'Quản trị bài đăng';
            break;
        default:
            $permission_name = 'Không có';
    }
@endphp
<div class="text-center">
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

<div class="card p-3 text-bg-light">
    <h2 class="text-center">Xin chào, <i>{{ $moderator->username }}</i>!!</h2>
    <h3 class="text-center">Trong ngày hôm nay {{ date('d-m-Y') }}, tính đến thời điểm {{ date('H:i') }}, chúng ta có</h3>
    <div class="row">
        <div class="col-sm-6">
            <p>Tổng lượt đọc: 0</p>
            <p>Tổng lượt bình luận: 0</p>
        </div>

        <div class="col-sm-6">
            <p>Số chương truyện được đăng tải: 0</p>
            <p>Số lượng người dùng đã đăng ký: 0</p>
        </div>
    </div>
</div>

<div class="text-center mt-2">
    <h2>Thông tin tài khoản</h2>
</div>
<div class="row">
    <div class="col-sm-6">
        <p>Tên tài khoản: <i>{{ $moderator->username }}</i></p>
    </div>

    <div class="col-sm-6">
        <p>Email: <i>{{ $moderator->email }}</i></p>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <p>Quyền hạn: <i>{{ $permission_name }}</i></p>
    </div>
</div>

@endsection