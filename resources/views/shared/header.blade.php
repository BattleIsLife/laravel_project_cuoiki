<nav class="navbar navbar-expand-sm navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="">
            <img src="{{ @asset('logo/favicon.jpeg') }}" alt="Logo" class="rounded-pill" style="width: 40px;">
        </a>
        <!-- Responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Các nav chính -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="">Danh sách truyện</a></li>
                <li class="nav-item"><a class="nav-link" href="">Các series truyện</a></li>
                <li class="nav-item" id="header_search_bar">
                    {{-- Tí nữa thay link mới sau --}}
                    <form class="d-flex" role="search" 
                          method="get" action="https://youtu.be/dQw4w9WgXcQ?si=VkmG2L8omiNp_FW-">
                        @csrf
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm tên truyện" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto" id="nav-auth">
                <li class="navbar-nav">
                    {{-- Sau khi đăng nhập user --}}
                    @auth('web')
                        <a class="nav-link" href="">Bắt đầu viết truyện</a>
                        <a class="nav-link" href="">Username here</a>
                        <a class="nav-link" href="">Đăng xuất</a>
                    @endauth

                    {{-- Sau khi đăng nhập moderator --}}
                    @auth('moderator')
                        <a class="nav-link" href="">Dashboard</a>
                        <a class="nav-link" href="">Username here</a>
                        <a class="nav-link" href="">Đăng xuất</a>
                    @endauth

                    {{-- Chưa đăng nhập/ Đã đăng xuất --}}
                    @guest()
                        <a class="nav-link" href="">Đăng nhập</a>
                        <a class="nav-link" href="">Đăng ký</a>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>