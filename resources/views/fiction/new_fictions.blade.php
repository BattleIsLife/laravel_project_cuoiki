@extends('main')
@section('content')
<div class="container-fluid p-4 mt-3">
    <div class="row">
        <div class="col-sm-3 d-flex mt-2 justify-content-center">
            <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                <img id="previewImg" src="#" alt="" class="img-fluid rounded" style="width: inherit; height: inherit; object-fit: cover;">
            </div>
        </div>

        <div class="col-sm-9">
            <div class="container-sm card p-4 mt-2">
                <form action="{{ route('user.new_fiction') }}" method="post" id="FictionForm"  enctype="multipart/form-data">
                    @csrf

                    <h1 class="text-center">Thêm truyện mới</h1>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                                id="fiction_name" placeholder="Nhập tên truyện" name="fiction_name" value="{{ old('fiction_name') }}">
                        <div class="invalid-feedback" id="fiction_name_Error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="series_id" class="form-label">Series truyện:</label>
                        <select name="series_id" id="series_id" class="form-select">
                            <option value="">Không có</option>
                            @foreach ($series as $item)
                                <option value="{{ $item->id }}" @selected(old('series_id') == $item->id)>{{ $item->series_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_description" class="form-label">Mô tả truyện:</label>
                        <textarea class="form-control" id="fiction_description" name="description" rows="5" placeholder="Nhập mô tả truyện">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_image" class="form-label">Bìa truyện <i>(Khuyến nghị ảnh có kích cỡ 108 x 170 hoặc có tỷ lệ tương đương)</i>:</label>
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
