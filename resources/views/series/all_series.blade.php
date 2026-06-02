@extends('main')
@section('title')
    Các series mới nhất
@endsection
@section('content')
<link rel="stylesheet" href="{{ @asset('css/all_fiction_series.css') }}">
<style>
    #header_search_bar
    {
        display: none;
    }   
</style>
<div class="container-sm mt-3">
    <form class="d-flex" role="search" method="get" action="{{ route('all_series') }}">
        <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên series, tác giả" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
    <h3 class="text-center mt-5">Danh sách series</h3>
    <div class="container-sm mt-3 md-3" id="fiction_series_list">
        @forelse ($allSeries as $series)
            <div class="card p-3 m-2 fiction_series">
                <a href="{{ route('series.detail', $series->id) }}">
                    <img src="{{ $series->image_link ? asset('storage/' . $series->image_link) : asset('logo/favicon.jpeg') }}" alt="{{ $series->series_name }}" style="width: 108px; height: 170px; object-fit: cover;">
                </a>
                <h5 class="text-center"><a href="{{ route('series.detail', $series->id) }}" class="fiction_series_link">{{ $series->series_name }}</a></h5>
                <p class="mb-1">Tác giả: <strong>{{ $series->author->username ?? 'Không rõ' }}</strong></p>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $allSeries->links('pagination::bootstrap-5') }}
            </div>
        @empty
            <div class="alert alert-info mt-3 text-center">Chưa có series nào.</div>
        @endforelse
    </div>
</div>
@endsection