@extends('main')
@section('title')
    Đọc truyện {{ $chapter->fiction->fiction_name }} - {{ $chapter->chapter_name }}
@endsection
@section('content')
<div class="container-sm mt-3 p-4">
    <h3 class="text-center">{{ $chapter->chapter_name }}</h3>
    <div class="container mt-2 p-3 card" style="max-width: 900px; min-height: 60vh;">
        {!! $chapter->content !!}
    </div>
</div>

@endsection