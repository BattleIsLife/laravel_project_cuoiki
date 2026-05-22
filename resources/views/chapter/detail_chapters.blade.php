@extends('main')
@section('content')
<div class="container-sm mt-3 p-4">
    <h3 class="text-center"></h3>
    <div class="container mt-2 p-3 card">
        
    </div>
</div>

<div class="container-sm mt-3 p-4 border-top d-flex flex-column">
    <form class="mb-3 border p-4" id="commentForm">
        @csrf
        <h4>Viết bình luận ở đây</h4>
        <input type="hidden" name="chapter_id" value=""> <!-- ID chapter hiện tại -->
        <textarea class="form-control" rows="4" id="comment_content" name="comment_content"></textarea>
        <p><small>Vui lòng hành xử như 1 người bình thường, ở đây không tiếp động vật</small></p>
        <button type="submit" class="btn btn-success mt-3" name="add_comment">Đăng tải bình luận</button>
    </form>

    <h5>Các bình luận của người đọc</h5>
    <!-- Bình luận được thêm bằng javascript -->
    
    <!-- Bình luận -->
    <div id="commentsList">
        
    </div>
</div>

<script src="{{ @asset('js/add_comment.js') }}"></script>

@endsection