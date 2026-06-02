@extends('admin.profile')
@section('title')
    Quản lý series
@endsection
@section('moderator_profile_component')
<link rel="stylesheet" href="{{ @asset('css/series_list.css') }}">
<h2 class="text-center">Danh sách series</h2>
<!-- The floating button -->
@if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="d-flex flex-column">
    <form class="d-flex" role="search" method="get" action="{{ route('admin.series_list') }}">
        <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên series, tác giả" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
    @if (count($allSeries) == 0)
        <div class="mt-3">
            <h4 class="text-center">Chưa có series nào</h4>
        </div>
    @else
        @foreach ($allSeries as $series)
            <div class="series card p-3 mt-3 ms-2 me-2">
                <img class="cover_image" src="{{ @asset('storage/' . $series->image_link) }}">
                <div class="series-info ms-3">
                    <a href="{{ @url('/series/' . $series->id) }}"><h4 class="series-title">{{ $series->series_name }}</h4></a>  
                    <p>Tác giả: <i>{{ $series->author->username }}</i></p>
                    <p>Ngày đăng tải: <i>{{ $series->created_at }}</i></p>
                    <p>Cập nhật lần cuối: <i>{{ $series->updated_at }}</i></p>
                    <div>
                        <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                           data-bs-toggle="modal"
                           data-bs-target="#deleteModal"
                           data-id="{{ $series->id }}"
                           data-name="{{ $series->series_name }}"
                           onclick="get_delete_id(this)">
                            Xóa series
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex flex-column justify-content-center mt-4">
            {{ $allSeries->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- The Modal -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Xác nhận xóa <strong id="delete_stuff"></strong>?</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="confirm_delete_btn" onclick="confirm_delete(this)" class="btn btn-danger">Xác nhận xóa</button>
            <button type="button" class="btn btn-success" id="btn-close-modal" data-bs-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

<script>
    const BASE_URL = "{{ url('/') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>

<script src="{{ @asset('js/moderator/delete_series.js') }}"></script>

@endsection