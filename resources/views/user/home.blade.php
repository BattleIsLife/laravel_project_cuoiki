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
        <h5 class="text-center">Chưa có thông báo nào</h5>
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
                        <img src="{{ @asset('storage/' . $fiction->image_link) }}" class="fiction_series_img">
                        <h5 class="text-center"><a href="" class="fiction_series_link">{{ $fiction->fiction_name }}</a></h5>
                        <p>Ngày đăng: <i>{{ $fiction->created_at }}</i></p>
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
                        <img src="{{ @asset('storage/' . $series->image_link) }}" class="fiction_series_img">
                        <h5 class="text-center"><a href="" class="fiction_series_link">{{ $series->series_name }}</a></h5>
                        <p>Ngày đăng: <i>{{ $series->created_at }}</i></p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
        
</div>


@endsection