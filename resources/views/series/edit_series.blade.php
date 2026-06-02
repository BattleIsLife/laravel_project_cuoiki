@extends('main')
@section('content')
@section('title')
    Chỉnh sửa series - {{ $series->series_name }}
@endsection

<ul class="nav nav-tabs justify-content-center">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#edit_series_info">Thông tin series</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#edit_chapter_list">Danh sách truyện thuộc series</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane container active" id="edit_series_info">
        @include('series.partial.edit_series_info')
    </div>
    <div class="tab-pane container fade" id="edit_chapter_list">
        @include('series.partial.edit_fiction_list')
    </div>
</div>

<script src="{{ @asset('js/edit_series.js') }}"></script>

@endsection