@extends('main')
@section('content')
<!-- Stylesheet for quilljs -->
<link href="{{ @asset('css/quill/quill_snow.css') }}" rel="stylesheet" />
<link href="{{ @asset('css/editor.css') }}" rel="stylesheet" />
<div class="mt-3 p-2">
    <div class="container-sm">
        <form id="editChapterForm" action="" method="post">
            @csrf
            <input type="hidden" readonly name="fiction_id" value="">
            <input type="hidden" readonly name="id" value="">
            <input class="form-control text-center fs-4 fst-italic" id="title" name="title" value="" placeholder="Tiêu đề chương ở đây!!" type="text">
            <p class="mt-3">Số thứ tự chương <input type="number" class="text-center" name="chapter_order" value="" min="0"></p>
            <div class="invalid-feedback" id="titleError"></div>
            <textarea readonly hidden name="content" id="hiddenContent">
                
            </textarea>
            <div class="container-sm d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-success mt-3">Lưu chương</button>
            </div>
        </form>

        <div class="container-sm d-flex justify-content-end mb-3">
            <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" data-bs-toggle="modal" data-bs-target="#deleteModal">Xóa chương</a>
        </div>

        <div id="editor" class="ql-editor">
            
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