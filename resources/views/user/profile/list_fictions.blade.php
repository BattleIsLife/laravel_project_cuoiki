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
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                        <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                        </svg> {{ $fiction->like_fiction_history_count }}</p>
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

        <div class="d-flex flex-column justify-content-center mt-4">
            {{ $fictions->links('pagination::bootstrap-5') }}
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
</script>

<script src="{{ @asset('js/delete_fiction.js') }}"></script>
@endsection