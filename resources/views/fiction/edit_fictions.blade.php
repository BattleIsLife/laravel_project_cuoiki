@extends('main')
@section('content')

<div class="container-fluid">
    <ul class="nav nav-tabs justify-content-center">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#edit_fiction_info">Thông tin truyện</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#edit_chapter_list">Mục lục chương</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane container active" id="edit_fiction_info">
        @include('fiction.partial.edit_fiction_info')
    </div>
    <div class="tab-pane container fade" id="edit_chapter_list">
        @include('fiction.partial.chapter_list')
    </div>
</div>
</div>
<script>
    const BASE_URL = "{{ url('/') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ @asset('js/edit_fiction.js') }}"></script>
<script src="{{ @asset('js/delete_fiction.js') }}"></script>
@endsection
