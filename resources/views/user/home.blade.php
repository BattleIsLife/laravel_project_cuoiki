@extends('main')

@section('content')
<link rel="stylesheet" href="{{ @asset('css/all_fiction_series.css') }}">
<div class="p-5 bg-secondary text-white text-center">
    <h1>Flan-fiction</h1>
    <p>Ươm mầm sự sáng tạo</p>
</div>
<div class="container-fluid mt-5">
    <div class="container">
        <h3 class="text-center">Thông báo của moderator</h3>
        @forelse ($posts as $post)
            <div class="card p-3 mt-3 text-center">
                <h4><a href="{{ route('home') }}">
                    {{ $post->title }}
                </a></h4>
                <p><i>Người đăng:</i> {{ $post->moderator->username }}</p>
                <p><i>Ngày đăng tải:</i> {{ $post->created_at }}</p>
                <p><i>Cập nhật lần cuối:</i> {{ $post->updated_at }}</p>
            </div>
        @empty
            <h5 class="text-center">Chưa có thông báo nào</h5>
        @endforelse
    </div>
    <div class="container mt-5">
        <h3 class="text-center">Các tiểu thuyết hot nhất!!</h3>
        {{-- Danh sách truyện wor đấy --}}
        <div class="container-sm mt-3 md-3" id="fiction_series_list">
            @if (count($hotFictions) == 0)
                <h5 class="text-center">Chưa có truyện hot nhất!!</h5>
            @else
                @foreach ($hotFictions as $fiction)
                    <div class="card p-3 m-2 fiction_series">
                        <a href="{{ route('fiction.detail', $fiction->id) }}">
                            <img src="{{ $fiction->image_link ? asset('storage/' . $fiction->image_link) : asset('logo/favicon.jpeg') }}" alt="{{ $fiction->fiction_name }}" style="width: 108px; height: 170px; object-fit: cover;">
                        </a>
                        <h5 class="text-center"><a href="{{ route('fiction.detail', $fiction->id) }}" class="fiction_series_link">{{ $fiction->fiction_name }}</a></h5>
                        <p class="mb-1">Tác giả: <strong>{{ $fiction->author->username ?? 'Không rõ' }}</strong></p>
                        <p class="mb-1">Lượt thích: {{ $fiction->like_fiction_history_count }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="container mt-5">
        <h3 class="text-center">Các series hot nhất!!</h3>
        <div class="container-sm mt-3 md-3" id="fiction_series_list">
            @if (count($hotSeries) == 0)
                <h5 class="text-center">Chưa có series hot nhất!!</h5>
            @else
                @foreach ($hotSeries as $series)
                    <div class="card p-3 m-2 fiction_series">
                        <a href="{{ route('home') }}">
                            <img src="{{ $series->image_link ? asset('storage/' . $series->image_link) : asset('logo/favicon.jpeg') }}" alt="{{ $series->series_name }}" style="width: 108px; height: 170px; object-fit: cover;">
                        </a>
                        <h5 class="text-center"><a href="{{ route('series.detail', $series->id) }}" class="fiction_series_link">{{ $series->series_name }}</a></h5>
                        <p class="mb-1">Tác giả: <strong>{{ $series->author->username ?? 'Không rõ' }}</strong></p>
                        <p class="mb-1">Lượt xem: {{ $series->chapters_sum_watch_count ?? 0 }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
        
</div>


@endsection