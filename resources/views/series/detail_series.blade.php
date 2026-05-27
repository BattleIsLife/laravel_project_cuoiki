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
                        <img id="previewImg" src="{{ @asset('storage/' . $series->image_link) }}" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
                    </div>
                </div>

                <div class="col-sm-9 mt-2">
                    <div class="container-sm card p-4">
                        <h1 class="text-center">Thông tin series</h1>

                        <h4 class="text-center mt-3 mb-3">{{ $series->series_name }}</h4>
                        <p>Tác giả: <strong>{{ $series->author->username ?? 'Không rõ' }}</strong></p>

                        <div class="mb-3">
                            <label for="series_description" class="form-label">Mô tả series:</label>

                            <textarea class="form-control" disabled readonly rows="10" >{{ $series->description }}</textarea>
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
                @forelse ($fictions as $fiction)
                    <div class="card p-3 mt-3">
                        <a href="{{ url('/fiction/' . $fiction->id) }}"><h5 class="mb-1">{{ $fiction->fiction_name }}</h5></a>
                        <p class="mb-0">Ngày đăng tải: {{ $fiction->created_at }}</p>
                        <p class="mb-0">Lượt thích: {{ $fiction->like_fiction_history_count }}</p>
                    </div>

                    <div class="d-flex flex-column justify-content-center mt-4">
                        {{ $fictions->links('pagination::bootstrap-5') }}
                    </div>
                @empty
                    <div class="alert alert-info mt-3 text-center">Truyện này chưa có chương nào.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection