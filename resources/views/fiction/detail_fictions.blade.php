@extends('main')
@section('content')
@section('title')
    Đọc truyện - {{ $fiction->fiction_name }}
@endsection
@php
    $moderator = auth()->guard('moderator')->user();
    $user =  auth()->guard('web')->user();
@endphp
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
                        <img id="previewImg" src="{{ $fiction->image_link ? asset('storage/' . $fiction->image_link) : asset('logo/favicon.jpeg') }}" alt="{{ $fiction->fiction_name }}" class="img-fluid rounded" style="width: inherit; height: inherit; object-fit: cover;">
                    </div>
                </div>

                <div class="col-sm-9 mt-2">
                    <div class="container-sm card p-4">
                        <h1 class="text-center">Thông tin truyện</h1>

                        <h4 class="text-center mt-3 mb-3">{{ $fiction->fiction_name }}</h4>
                        <p>Tác giả: <strong>{{ $fiction->author->username ?? 'Không rõ' }}</strong></p>
                        <p>Series: <strong>{{ $fiction->series->series_name ?? 'Không có' }}</strong></p>
                        <p>Lượt thích: <span id="likeCount">{{ $fiction->like_fiction_history_count }}</span></p>

                        @if ($user)
                            <div>
                                <button class="btn btn-success btn-disabled" id="likeButton" onclick="like_fiction(this)"
                                        data-bs-id="{{ $fiction->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                    </svg>
                                    Thích truyện này
                                </button>
                            </div>
                        @endif

                        <div class="mb-3 mt-3">
                            <label for="fiction_description" class="form-label">Mô tả truyện:</label>

                            <textarea class="form-control" disabled readonly rows="10" >{{ $fiction->description }}</textarea>
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
                @forelse ($chapters as $chapter)
                    <div class="card p-3 mt-3 chapter">
                        <a href="{{ url('/chapter/' . $chapter->id) }}"><h5 class="mb-1">{{ $chapter->chapter_name }}</h5></a>
                        <p class="mb-0">Lượt xem: {{ $chapter->watch_count }}</p>
                        <p class="mb-0">Lượt thích: <span class="likeChapterCount">{{ $chapter->like_chapter_history_count }}</span></p>
                        @if ($user)
                            <div>
                                <button class="btn btn-success btn-disabled likeChapterButton" onclick="like_chapter(this)"
                                        data-bs-id="{{ $chapter->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                    </svg>
                                    Thích chương này
                                </button>
                            </div>
                        @endif
                        @if($moderator && $moderator->permission == 'user_moderator')
                            <div>
                                <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="{{ $chapter->id }}"
                                data-name="{{ $chapter->chapter_name }}"
                                onclick="get_delete_id(this)">
                                    Xóa chương
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-column justify-content-center mt-4">
                        {{ $chapters->links('pagination::bootstrap-5') }}
                    </div>
                @empty
                    <div class="alert alert-info mt-3 text-center">Truyện này chưa có chương nào.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- For user moderator --}}
@if($moderator && $moderator->permission == 'user_moderator')
    <!-- The Modal -->
    <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa <strong id="delete_stuff"></strong>?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="confirm_delete_btn" onclick="confirm_delete(this)" class="btn btn-danger">Xác nhận xóa</button>
                <button type="button" class="btn btn-success" id="btn-close-modal" data-bs-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
@endif
<script>
    const BASE_URL = "{{ url('') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ @asset('js/interactions/like.js') }}"></script>
{{-- For user moderator --}}
@if($moderator && $moderator->permission == 'user_moderator')
    <script src="{{ @asset('js/moderator/delete_chapter.js') }}"></script>
@endif

@endsection
