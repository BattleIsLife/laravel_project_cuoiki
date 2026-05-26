@extends('main')
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
        <form action="{{ @url('/author/edit_fiction/' . $fiction->id . '/new_chapter') }}" method="post">
            @csrf
            <input type="hidden" readonly name="fiction_id" value="">
            <input class="form-control text-center fs-4 fst-italic" name="chapter_name" value="Untitled" placeholder="Tiêu đề chương ở đây!!" type="text">
            <p class="mt-3">Số thứ tự chương <input type="number" class="text-center" name="chapter_order" value="{{ $nextOrder }}" min="1"></p>
            <textarea hidden readonly name="content" id="hiddenContent">
            </textarea>
            <label for="save_as_draft">Lưu dưới dạng draft <input type="checkbox" name="save_as_draft"></label>
            <div class="container-sm d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-success mt-3">Lưu chapter</button>
            </div>
        </form>
        <div id="editor" class="ql-editor">
            <p>Hello World!</p>
            <p>Some initial <strong>bold</strong> text</p>
            <p><br /></p>
        </div>
    </div>
    
    
</div>
<script src="{{ @asset('js/quill/quill.js') }}"></script>
<script src="{{ @asset('js/quill/quill_config.js') }}"></script>
@endsection