@extends('main')

@section('content')

<div class="p-5 bg-secondary text-white text-center">
    <h1>Flan-fiction</h1>
    <p>Ươm mầm sự sáng tạo</p>
</div>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-sm-3 text-center">
            <h2>Leader</h2>
            <div>
                <img src="{{ @asset('logo/anh ca nhan.jpg') }}" alt="Logo" class="rounded-pill" style="width: 100px;">
            </div>
            <p>Trần Tuấn Đạt</p>
            <a href="">Thông tin thêm</a>
            <hr class="d-sm-none">
        </div>

        <div class="col-sm-9 pe-5">
            <h3 class="text-center">Các chương truyện mới nhất!!</h3>
        </div>
    </div>
</div>


@endsection