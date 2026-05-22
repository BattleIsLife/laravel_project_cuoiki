@extends('main')
@section('content')
<ul class="nav nav-tabs justify-content-center">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#fiction_info">Thông tin truyện</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#chapter_list">Mục lục chương</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <!-- Thông tin truyện -->
    <div class="tab-pane container active" id="fiction_info">
        <div class="container p-4 mt-3">
            <div class="row">
                <div class="col-sm-3 d-flex mt-2 justify-content-center">
                    <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                        <img id="previewImg" src="" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
                    </div>
                </div>

                <div class="col-sm-9 mt-2">
                    <div class="container-sm card p-4">
                        <h1 class="text-center">Thông tin truyện</h1>

                        <h4 class="text-center mt-3 mb-3"></h4>

                        <div class="mb-3">
                            <label for="fiction_description" class="form-label">Mô tả truyện:</label>

                            <textarea class="form-control" disabled readonly rows="10" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List chương truyện -->
    <div class="tab-pane container fade" id="chapter_list">
        <div class="container-sm card p-4 mt-5">
            <h2 class="text-center">Mục lục chương</h2>
            <div class="d-flex flex-column">
               
            </div>
        </div>
    </div>
</div>

@endsection