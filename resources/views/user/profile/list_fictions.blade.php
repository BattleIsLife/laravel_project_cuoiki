@extends('user.profile')
@section('user_profile_component')
<link rel="stylesheet" href="{{ @asset('css/fiction_list.css') }}">
<h2 class="text-center">Danh sách truyện</h2>

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

<!-- The floating button -->
<div class="container d-flex justify-content-end">
    <a class="btn btn-success" href="{{-- route('user.new_fiction') --}}">
        Thêm truyện mới
    </a>
</div>
<div class="d-flex flex-column">
    @if (count($fictions) == 0)
        <div class="mt-3">
            <h4 class="text-center">Bạn chưa có truyện nào, bấm vào nút <i>"Thêm truyện mới"</i> để bắt đầu viết truyện của bạn</h4>
        </div>
    @else
        @foreach ($fictions as $fiction)
            <div class="fiction card p-3 mt-3 ms-2 me-2">
                <img class="cover_image" src="{{ @asset('storage/' . $fiction->image_link) }}">
                <div class="fiction-info ms-3">
                    <a href="{{ @url('author/edit_fiction/' . $fiction->id) }}"><h4 class="fic-title">{{ $fiction->fiction_name }}</h4></a>  
                    <p>Ngày đăng tải: <i>{{ $fiction->created_at }}</i></p>
                    <p>Cập nhật lần cuối: <i>{{ $fiction->updated_at }}</i></p>
                    <div>
                        <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                           data-bs-toggle="modal"
                           data-bs-target="#deleteModal"
                           data-id="{{ $fiction->id }}"
                           data-name="{{ $fiction->fiction_name }}"
                           onclick="get_delete_id(this)">
                            Xóa truyện
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
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
</script>

<script src="{{ @asset('js/delete_fiction.js') }}"></script>
@endsection