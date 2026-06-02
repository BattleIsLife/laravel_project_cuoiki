@php
    $moderator = auth()->guard('moderator')->user();
    $user = auth()->guard('web')->user();
@endphp
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ @asset('logo/favicon.jpeg') }}" alt="Logo" class="rounded-pill" style="width: 40px;">
        </a>
        <!-- Responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Các nav chính -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ @route('all_fictions') }}">Danh sách truyện</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ @route('all_series') }}">Các series truyện</a></li>
                <li class="nav-item" id="header_search_bar">
                    {{-- Tí nữa thay link mới sau --}}
                    <form class="d-flex" role="search" 
                          method="get" action="{{ route('all_fictions') }}">
                        <input class="form-control me-2" type="search" name="q" placeholder="Tìm kiếm tên truyện, tác giả" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto" id="nav-auth">
                <li class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ @route('about_me') }}">About us</a></li>
                    {{-- Sau khi đăng nhập user --}}
                    @if ($user)
                        <a class="nav-link" href="{{ route('user.new_fiction') }}">Bắt đầu viết truyện</a>
                        <a class="nav-link fw-bold" href="{{ route('user.profile') }}">{{ auth()->guard('web')->user()->username }}</a>
                        <a href="{{ url('/author/logout') }}" class="nav-link fw-bold text-danger" 
                            onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
                            Đăng xuất
                        </a>

                        <!-- Hidden Logout Form -->
                        <form id="user-logout-form" action="{{ url('/author/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    {{-- Sau khi đăng nhập moderator --}}
                    @elseif($moderator)
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ auth()->guard('moderator')->user()->username }}</a>
                        <a href="{{ route('admin.logout') }}" class="nav-link fw-bold text-danger" 
                            onclick="event.preventDefault(); document.getElementById('moderator-logout-form').submit();">
                            Đăng xuất
                        </a>

                        <!-- Hidden Logout Form -->
                        <form id="moderator-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    {{-- Chưa đăng nhập/ Đã đăng xuất --}}
                    @else
                        <a class="nav-link" href="{{ route('user.login') }}">Đăng nhập</a>
                        <a class="nav-link" href="{{ route('user.register') }}">Đăng ký</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>
