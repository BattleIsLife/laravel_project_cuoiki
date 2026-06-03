@extends('admin.profile')
@section('title')
    Quản lý bài đăng
@endsection
@section('moderator_profile_component')
@php
    $moderator = auth()->guard('moderator')->user()
@endphp
<div class="p-2 mt-2">
    <h2 class="text-center">Danh sách bài đăng</h2>

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

    <form class="d-flex" role="search" method="get" action="{{ route('admin.post_list') }}">
        <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên bài đăng, người tạo" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>

    @if ($moderator->permission === 'post_moderator')
        <div class="container d-flex justify-content-end mt-3">
            <a class="btn btn-success" style="width: fit-content" href="{{ route('admin.new_post') }}">
                Thêm bài đăng mới
            </a>
        </div>
    @endif

    <div class="d-flex flex-column">
        @forelse ($posts as $post)
            <div class="card p-4 mt-3 post">
                <h4><a href="@if ($moderator->id === $post->moderator_id) 
                                {{ route('admin.edit_post', $post->id) }} 
                            @else {{ route('post.detail', $post->id) }} @endif">
                    {{ $post->title }}
                </a></h4>
                <p><i>Ngày đăng tải:</i> {{ $post->created_at }}</p>
                <p><i>Cập nhật lần cuối:</i> {{ $post->updated_at }}</p>
                <p><i>Người đăng bài:</i> {{ $post->moderator->username }}</p>
                @if ($moderator->id === $post->moderator_id)
                    <div class="mt-3">
                        <a class="btn btn-primary" style="width: fit-content; height: fit-content;" href="{{ route('post.detail', $post->id) }}">
                            Xem bài đăng
                        </a>
                    </div>

                    <div class="mt-3">
                        <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="{{ $post->id }}"
                            data-name="{{ $post->title }}"
                            onclick="get_delete_id(this)">
                            Xóa bài đăng
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="alert alert-info mt-3 text-center">Chưa có bài đăng nào, bấm Thêm bài đăng mới để thêm bài đăng.</div>
        @endforelse
    </div>
</div>

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
<script>
    const BASE_URL = "{{ url('') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ @asset('js/moderator/delete_post.js') }}"></script>
@endsection