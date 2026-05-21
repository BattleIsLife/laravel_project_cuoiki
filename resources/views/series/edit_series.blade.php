@extends('main')
@section('content')
<div class="container-fluid p-4 mt-3">
    <div class="row">
        <div class="col-sm-3 d-flex mt-2 justify-content-center">
            <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                <img id="previewImg" src="" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
            </div>
        </div>

        <div class="col-sm-9">
            <div class="container-sm card p-4 mt-2">
                <form action="" method="post" id="seriesForm"  enctype="multipart/form-data">
                    @csrf

                    <h1 class="text-center">Sửa series</h1>
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

                    <input type="hidden" readonly name="series_id" value=">">

                    <div class="mb-3 mt-3">
                        <label for="series_name" class="form-label">Tên series:</label>
                        <input type="text" class="form-control" 
                                id="series_name" placeholder="Nhập tên series" name="series_name" value="">
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

                    <button type="submit" class="btn btn-success w-100" name="add_series">Sửa series</button>
                </form>
                <div class="container-sm d-flex justify-content-end mt-3 mb-3">
                    <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" data-bs-toggle="modal" data-bs-target="#deleteModal">Xóa series</a>
                </div>
            </div>
        </div>

             <!-- The Modal -->
        <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận muốn xóa <strong id="delete_stuff"></strong>?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <form action="" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                    </form>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ @asset('js/edit_series.js') }}"></script>

@endsection