@extends('main')
@section('content')
<div class="container-fluid p-4 mt-3">
    <div class="row">
        <div class="col-sm-3 d-flex mt-2 justify-content-center">
            <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                <img id="previewImg" src="#" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
            </div>
        </div>

        <div class="col-sm-9">
            <div class="container-sm card p-4 mt-2">
                <form action="" method="post" id="FictionForm"  enctype="multipart/form-data">
                    @csrf

                    <h1 class="text-center">Thêm truyện mới</h1>
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

                    <div class="mb-3 mt-3">
                        <label for="fiction_name" class="form-label">Tên truyện:</label>
                        <input type="text" class="form-control" 
                                id="fiction_name" placeholder="Nhập tên truyện" name="fiction_name">
                        <div class="invalid-feedback" id="fiction_name_Error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_description" class="form-label">Mô tả truyện:</label>
                        <textarea class="form-control" id="fiction_description" name="description" rows="5" placeholder="Nhập mô tả truyện"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_image" class="form-label">Bìa truyện <i>(Khuyến nghị ảnh có kích cỡ 108 x 170 hoặc có tý lệ tương đương)</i>:</label>
                        <input class="form-control" type="file" accept="image/*" name="image" onchange="previewImage(event)">
                        <div class="invalid-feedback" id="fiction_image_Error"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" name="add_fiction">Thêm truyện mới</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ @asset('js/edit_fiction.js') }}"></script>
@endsection