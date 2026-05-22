@extends('main')
@section('content')
<div class="container-fluid p-3">
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-pills flex-column">
                <!-- Tab chuyển đổi -->
                <li class="nav-item">
                    <a class="nav-link" href="">Thông tin tài khoản</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">Danh sách truyện</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Danh sách series</a>
                </li>

                <li class="nav-item mt-3">
                    <a class="nav-link bg-secondary text-white" href="">Xóa tài khoản</a>
                </li>
            </ul>
            <hr class="d-sm-none">
        </div>
        <div class="col-sm-9">
            @yield('user_profile_component')
        </div>
    </div>
</div>
@endsection