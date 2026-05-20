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
            </ul>

            <ul class="navbar-nav ms-auto" id="nav-auth">
                <li class="nav-item d-flex flex-row">
                    <a class="nav-link" href="">Đăng nhập</a>
                    <a class="nav-link" href="">Đăng ký</a>
                </li>
            </ul>
        </div>
    </div>
</nav>