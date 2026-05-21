@extends('main')
@section('content')
<link rel="stylesheet" href="{{ @asset('css/fiction_list.css') }}">
<ul class="nav nav-tabs justify-content-center">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#series_info">Thông tin series</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#fiction_list">Danh sách truyện thuộc series</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <!-- Thông tin series -->
    <div class="tab-pane container active" id="series_info">
        <div class="container p-4 mt-3">
            <div class="row">
                <div class="col-sm-3 d-flex mt-2 justify-content-center">
                    <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                        <img id="previewImg" src="" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
                    </div>
                </div>

                <div class="col-sm-9 mt-2">
                    <div class="container-sm card p-4">
                        <h1 class="text-center">Thông tin series</h1>

                        <h4 class="text-center mt-3 mb-3"></h4>

                        <div class="mb-3">
                            <label for="series_description" class="form-label">Mô tả series:</label>

                            <textarea class="form-control" disabled readonly rows="10" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List truyện -->
    <div class="tab-pane container fade" id="fiction_list">
        <div class="container-sm card p-4 mt-5">
            <h2 class="text-center">Danh sách truyện thuộc series</h2>
            <div class="d-flex flex-column">
      
            </div>
        </div>
    </div>
</div>
@endsection