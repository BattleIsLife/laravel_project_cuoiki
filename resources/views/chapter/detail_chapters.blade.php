@extends('main')
@section('content')
@php
    $user = auth()->guard('web')->user();
@endphp
<div class="container-sm mt-3 p-4">
    <h3 class="text-center">{{ $chapter->chapter_name }}</h3>
    <div class="container mt-2 p-3 card" style="max-width: 900px; min-height: 60vh;">
        {!! $chapter->content !!}
    </div>
</div>

<div class="container-sm mt-3 p-4 border-top d-flex flex-column">
    <form class="mb-3 border p-4" id="commentForm">
        <h4>Viết bình luận ở đây</h4>
        @if (!$user) <p>Vui lòng đăng nhập để bình luận</p> @endif
        <textarea class="form-control" rows="4" id="comment_content" name="comment_content" @if (!$user) disabled @endif></textarea>
        <p><small>Lời nói của bạn có trọng lượng. Hãy cẩn thận nếu không là TÙ NGAY</small></p>
        <button type="submit" class="btn btn-success mt-3" id="submit_comment" @if (!$user) disabled @endif >Đăng tải bình luận</button>
    </form>

    <h5>Các bình luận của người đọc</h5>
    <!-- Bình luận được thêm bằng javascript -->
    
    <!-- Bình luận -->
    <div id="commentsList">
        @forelse ($comments as $comment)
            <div class="container-sm mt-2 p-3 border main-comment-wrapper comment-box">
                <div class="border-bottom mb-2">
                    <h6>{{ $comment->user->username }}</h6>
                    <p><i>{{ $comment->created_at }}</i></p>
                    <p>Tổng điểm: <span class="total-score">{{ $comment->total_score ?? 0 }}</span></p>
                </div>
                <textarea class="form-control" disabled readonly required rows="6" >{{ $comment->content }}</textarea>
                @if ($user)
                    <div class="mt-2 reply_comment"><button class="btn btn-primary" onclick="reply_comment(this, '{{ $comment->id }}')">Phản hồi bình luận</button></div>
                    <div class="d-flex flex-row mt-2">
                        <button class="btn btn-success" data-id="{{ $comment->id }}" onclick="upvote_comment(this, 1)">Upvote</button>
                        <button class="btn btn-danger" data-id="{{ $comment->id }}" onclick="upvote_comment(this, -1)">Downvote</button>
                    </div>
                @endif
                <div class="child_comment_list">
                    @foreach ($comment->child_comment as $child)
                        <div class="container-sm mt-2 p-3 ps-5 border comment-box">
                            <div class="border-bottom mb-2">
                                <h6>{{ $child->user->username }}</h6>
                                <p><i>{{ $child->created_at }}</i></p>
                                <p>Tổng điểm: <span class="total-score">{{ $child->total_score ?? 0}}</span></p>
                            </div>
                            <textarea class="form-control" disabled readonly required rows="6" >{{ $child->content }}</textarea>
                            @if ($user)
                                <div class="d-flex flex-row mt-2">
                                    <button class="btn btn-success" data-id="{{ $child->id }}" onclick="upvote_comment(this, 1)">Upvote</button>
                                    <button class="btn btn-danger" data-id="{{ $child->id }}" onclick="upvote_comment(this, -1)">Downvote</button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p id="no_comment_message"><small>Chưa có bình luận, hãy bình luận đi :)</small></p>
        @endforelse
        <div class="d-flex justify-content-center mt-4">
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    const BASE_URL = "{{ url('') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}"
    const CHAPTER_ID = "{{ $chapter->id }}";
</script>
<script src="{{ @asset('js/add_chapter_comment.js') }}"></script>
<script src="{{ @asset('js/interactions/upvote_comment.js') }}"></script>

@endsection