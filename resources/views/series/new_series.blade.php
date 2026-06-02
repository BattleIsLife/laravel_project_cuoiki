@extends('main')
@section('content')
@section('title')
    Tạo series mới
@endsection
<div class="container-fluid p-4 mt-3">
    <div class="row">
        <div class="col-sm-3 d-flex mt-2 justify-content-center">
            <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                <img id="previewImg" src="#" alt="" class="img-fluid rounded" style="width: inherit; height: inherit; object-fit: cover;">
            </div>
        </div>

        <div class="col-sm-9">
            <div class="container-sm card p-4 mt-2">
                <form action="{{ url('/author/new_series') }}" method="post" id="seriesForm"  enctype="multipart/form-data">
                    @csrf

                    <h1 class="text-center">Thêm series mới</h1>
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0" style="padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="mb-3 mt-3">
                        <label for="series_name" class="form-label">Tên series:</label>
                        <input type="text" class="form-control" 
                                id="series_name" placeholder="Nhập tên series" name="series_name">
                        <div class="invalid-feedback" id="series_name_Error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="series_description" class="form-label">Mô tả series:</label>
                        <textarea class="form-control" id="series_description" name="description" rows="5" placeholder="Nhập mô tả series"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="series_image" class="form-label">Ảnh bìa <i>(Khuyến nghị ảnh có kích cỡ 108 x 170 hoặc có tý lệ tương đương)</i>:</label>
                        <input class="form-control" type="file" accept="image/*" name="image" onchange="previewImage(event)">
                        <div class="invalid-feedback" id="series_image_Error"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" name="add_series">Thêm series mới</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ @asset('js/edit_series.js') }}"></script>

@endsection