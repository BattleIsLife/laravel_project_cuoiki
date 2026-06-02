@extends('main')
@section('title')
    Chỉnh sửa chương truyện - {{ $chapter->chapter_name }}
@endsection
@section('content')
<!-- Stylesheet for quilljs -->
<link href="{{ @asset('css/quill/quill_snow.css') }}" rel="stylesheet" />
<link href="{{ @asset('css/editor.css') }}" rel="stylesheet" />
<div class="mt-3 p-2">
    <div class="container-sm">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0" style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="editChapterForm" action="{{ @url('/author/edit_fiction/' . $fiction->id . '/edit_chapter/' . $chapter->id) }}" method="post">
            @csrf
            @method('PUT')
            <input class="form-control text-center fs-4 fst-italic" id="title" name="chapter_name" value="{{ $chapter->chapter_name }}" placeholder="Tiêu đề chương ở đây!!" type="text">
            <p class="mt-3">Số thứ tự chương <input type="number" class="text-center" name="chapter_order" value="{{ $chapter->chapter_order }}" min="1"></p>
            <div class="invalid-feedback" id="titleError"></div>
            <textarea readonly hidden name="content" id="hiddenContent">{!! $chapter->content !!}</textarea>
            <label for="save_as_draft">Lưu dưới dạng draft <input type="checkbox" name="save_as_draft" @if($chapter->is_posted == 0) checked @endif></label>
            <div class="container-sm d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-success mt-3">Lưu chương</button>
            </div>
        </form>

        <div id="editor" class="ql-editor">
            {!! $chapter->content !!}
        </div>
    </div>
    
    
</div>

<!-- The Modal -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Xác nhận muốn xóa <strong id="delete_stuff"></strong>?</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <form action="" method="post">
                @csrf
                <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            </form>
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
</div>
<script src="{{ @asset('js/quill/quill.js') }}"></script>
<script src="{{ @asset('js/quill/quill_config.js') }}"></script>
<script src="{{ @asset('js/edit_chapter.js') }}"></script>
@endsection