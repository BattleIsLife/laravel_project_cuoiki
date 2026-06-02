@extends('main')
@section('content')
@section('title')
    Series - {{ $series->series_name }}
@endsection
@php
    $moderator = auth()->guard('moderator')->user();
    $user =  auth()->guard('web')->user();
@endphp
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
                        <img id="previewImg" src="{{ @asset('storage/' . $series->image_link) }}" alt="" class="img-fluid rounded" style="width: inherit; height: inherit; object-fit: cover;">
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
                        <p class="mb-0">Lượt thích: <span class="likeFictionCount">{{ $fiction->like_fiction_history_count }}</span></p>
                        @if ($user)
                            <div>
                                <button class="btn btn-success btn-disabled likeFictionButton" onclick="like_fiction_in_series(this)"
                                        data-bs-id="{{ $fiction->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                    </svg>
                                    Thích truyện này
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-column justify-content-center mt-4">
                        {{ $fictions->links('pagination::bootstrap-5') }}
                    </div>
                @empty
                    <div class="alert alert-info mt-3 text-center">Series này chưa có truyện nào.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
    const BASE_URL = "{{ url('') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ @asset('js/interactions/like.js') }}"></script>
@endsection