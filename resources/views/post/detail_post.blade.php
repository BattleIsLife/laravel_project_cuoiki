@extends('main')
@section('title')
    Bài đăng - {{ $post->title }}
@endsection
@section('content')
<div class="container-sm mt-3 p-4">
    <h3 class="text-center">{{ $post->title }}</h3>
    <div class="container mt-2 p-3 card" style="max-width: 900px; min-height: 60vh;">
        {{ $post->description }}
    </div>
</div>

<div class="container-sm mt-3 p-4 border-top d-flex flex-column">
    <form class="mb-3 border p-4" id="commentForm">
        <h4>Viết bình luận ở đây</h4>
        @if (!$user) <p>Vui lòng đăng nhập để bình luận</p> @endif
        <textarea class="form-control" rows="4" id="comment_content" name="content" @if (!$user) disabled @endif></textarea>
        <p><small>Lời nói của bạn có trọng lượng. Hãy cẩn thận nếu không là TÙ NGAY</small></p>
        <button type="submit" class="btn btn-success mt-3" id="submit_comment" @if (!$user) disabled @endif >Đăng tải bình luận</button>
    </form>

    <h5>Các bình luận của người đọc</h5>
    <!-- Bình luận được thêm bằng javascript -->
    
    <!-- Bình luận -->
    <div id="commentsList">
        @forelse ($comments as $comment)
            <div class="container-sm mt-2 p-3 border main-comment-wrapper comment-box" data-id="{{ $comment->id }}">
                <div class="border-bottom mb-2">
                    <h6>{{ $comment->user->username }}</h6>
                    <p><i>{{ $comment->created_at }}</i></p>
                    <p>Tổng điểm: <span class="total-score">{{ $comment->total_score ?? 0 }}</span></p>
                </div>
                <textarea class="form-control" disabled readonly required rows="6" >{{ $comment->content }}</textarea>
                @if($moderator && $moderator->permission == 'post_moderator')
                    <div class="mt-2">
                        <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                        data-bs-toggle="modal"
                        data-bs-target="#moderatorDeleteCommentModal"
                        onclick="moderator_delete_comment(this)">
                            Xóa bình luận
                        </a>
                    </div>
                @endif
                @if ($user)
                    <div class="mt-2 reply_comment"><button class="btn btn-primary" onclick="reply_comment(this, '{{ $comment->id }}')">Phản hồi bình luận</button></div>
                    <div class="d-flex flex-row mt-2">
                        <button class="btn btn-success" onclick="upvote_post_comment(this, 1)">Upvote</button>
                        <button class="btn btn-danger" onclick="upvote_post_comment(this, -1)">Downvote</button>
                        @if ($user->id === $comment->user_id)
                            <button class="btn btn-secondary" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#userDeleteCommentModal"
                                    onclick="user_delete_comment(this)">
                                    Gỡ bình luận
                            </button>
                        @endif
                    </div>
                @endif

                <div class="child_comment_list">
                    @foreach ($comment->child_comment as $child)
                        <div class="container-sm mt-2 p-3 ps-5 border comment-box" data-id="{{ $child->id }}">
                            <div class="border-bottom mb-2">
                                <h6>{{ $child->user->username }}</h6>
                                <p><i>{{ $child->created_at }}</i></p>
                                <p>Tổng điểm: <span class="total-score">{{ $child->total_score ?? 0}}</span></p>
                            </div>
                            <textarea class="form-control" disabled readonly required rows="6" >{{ $child->content }}</textarea>
                            @if($moderator && $moderator->permission == 'post_moderator')
                                <div class="mt-2">
                                    <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#moderatorDeleteCommentModal"
                                
                                    onclick="moderator_delete_comment(this)">
                                        Xóa bình luận
                                    </a>
                                </div>
                            @endif
                            @if ($user)
                                <div class="d-flex flex-row mt-2">
                                    <button class="btn btn-success" onclick="upvote_post_comment(this, 1)">Upvote</button>
                                    <button class="btn btn-danger" onclick="upvote_post_comment(this, -1)">Downvote</button>
                                    @if ($user->id === $comment->user_id)
                                        <button class="btn btn-secondary" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#userDeleteCommentModal" 
                                                onclick="user_delete_comment(this)">
                                                Gỡ bình luận
                                        </button>
                                    @endif
                                    @if($moderator && $moderator->permission == 'post_moderator')
                                        <div>
                                            <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#moderatorDeleteModal"
                                            data-name="{{ $chapter->chapter_name }}"
                                            onclick="get_delete_id(this)">
                                                Xóa bình luận
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                    {{-- <div class="d-flex justify-content-center mt-4">
                        {{ $comment->child_comment->links('pagination::bootstrap-5') }}
                    </div> --}}
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

@if ($user)
    <!-- Modal Xóa Comment người dùng -->
    <div class="modal" id="userDeleteCommentModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận muốn xóa bình luận này?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="user_confirm_delete_comment" onclick="user_confirm_delete_comment(this)">Xác nhận xóa</button>
                <button type="button" class="btn btn-success" id="close_user_delete_comment_modal" data-bs-dismiss="modal" onclick="user_close_button(this)">Close</button>
            </div>

            </div>
        </div>
    </div>
@endif

@if($moderator && $moderator->permission == 'post_moderator')
    <!-- Modal Xóa Comment người dùng -->
    <div class="modal" id="moderatorDeleteCommentModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận muốn xóa bình luận này?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="moderator_confirm_delete_comment" onclick="moderator_confirm_delete_comment(this)">Xác nhận xóa</button>
                <button type="button" class="btn btn-success" id="close_moderator_delete_comment_modal" data-bs-dismiss="modal" onclick="moderator_close_button(this)">Close</button>
            </div>

            </div>
        </div>
    </div>
@endif

<script>
    const BASE_URL = "{{ url('') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}"
    const POST_ID = "{{ $post->id }}";
</script>
<script src="{{ @asset('js/add_post_comment.js') }}"></script>
<script src="{{ @asset('js/delete_post_comment.js') }}"></script>

@if($moderator && $moderator->permission == 'post_moderator')
    <script src="{{ @asset('js/moderator/delete_post_comment.js') }}"></script>
@endif

<script src="{{ @asset('js/interactions/upvote_comment.js') }}"></script>

@endsection