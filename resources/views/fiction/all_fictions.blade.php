@extends('main')

@section('content')
<link rel="stylesheet" href="{{ @asset('css/all_fiction_series.css') }}">
<style>
    #header_search_bar
    {
        display: none;
    }
</style>
<div class="container-sm mt-3">
    <form class="d-flex" role="search" method="get" action="{{ route('all_fictions') }}">
        <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên truyện" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
    <h3 class="text-center mt-5">Danh sách truyện</h3>
    <div class="container-sm mt-3 md-3" id="fiction_series_list">
        @forelse ($fictions as $fiction)
            <div class="card p-3 mt-3 d-flex flex-row gap-3">
                <a href="{{ route('fiction.detail', $fiction->id) }}">
                    <img src="{{ $fiction->image_link ? asset('storage/' . $fiction->image_link) : asset('logo/favicon.jpeg') }}" alt="{{ $fiction->fiction_name }}" style="width: 108px; height: 170px; object-fit: cover;">
                </a>
                <div class="flex-grow-1">
                    <a href="{{ route('fiction.detail', $fiction->id) }}" class="text-decoration-none text-dark">
                        <h4>{{ $fiction->fiction_name }}</h4>
                    </a>
                    <p class="mb-1">Tác giả: <strong>{{ $fiction->author->username ?? 'Không rõ' }}</strong></p>
                    <p class="mb-1">Series: <strong>{{ $fiction->series->series_name ?? 'Không có' }}</strong></p>
                    <p class="mb-1">Lượt thích: {{ $fiction->like_fiction_history_count }}</p>
                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($fiction->description, 220) }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-info mt-3 text-center">Chưa có truyện nào.</div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $fictions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
