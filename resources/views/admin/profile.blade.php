@extends('main')
@section('content')
<style>
    #header_search_bar
    {
        display: none;
    }
</style>
<div class="container-fluid p-3">
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-pills flex-column">
                <!-- Tab chuyển đổi -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>

                {{-- Chỉ user moderator --}}
                @if ($moderator->permission === 'user_moderator')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.fiction_list') }}">Danh sách truyện</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.series_list') }}">Danh sách series</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.user_list') }}">Danh sách người dùng</a>
                    </li>
                @endif

                {{-- Trừ none ra --}}
                @if ($moderator->permission !== 'none')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.post_list') }}">Danh sách bài đăng</a>
                    </li>
                @endif

                {{-- Chỉ admin --}}
                @if ($moderator->permission === 'admin')
                    <li class="nav-item mt-3">
                        <a class="nav-link bg-secondary text-white" href="{{ route('admin.moderator_list') }}">Danh sách quản trị viên</a>
                    </li>

                    <li class="nav-item mt-3">
                        <a class="nav-link bg-secondary text-white" href="{{ route('admin.register') }}">Tạo quản trị viên mới</a>
                    </li>
                @endif
                
            </ul>
            <hr class="d-sm-none">
        </div>
        <div class="col-sm-9">
            @yield('moderator_profile_component')
        </div>
    </div>
</div>
@endsection