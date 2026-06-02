@extends('admin.profile')
@section('title')
    Quản lý người dùng
@endsection
@section('moderator_profile_component')
<div class="text-center">
    <h2>Danh sách người dùng</h2>
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
</div>
<form class="d-flex" role="search" method="get" action="{{ route('admin.user_list') }}">
        <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên người dùng, email" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
<div class="table-responsive-md">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Username</th>
                <th>Email</th>
                <th>Ngày đăng ký</th>
                <th>Trạng thái</th>
                <th>Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            @php
                $stt = 1;
            @endphp
            @forelse ($users as $user)
                <tr>
                    <td>{{ $stt }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                    @php
                        $stt++;
                        $status = "Còn hoạt động";
                        if($user->deleted_at)
                            $status = "Đã xóa";
                        if($user->blocked_until)
                            $status = "Bị chặn";
                    @endphp
                    <td>{{ $status }}</td>
                    <td><button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#changeInfoModal"
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                                data-email="{{ $user->email }}"
                                data-blocked-until="{{ $user->blocked_until }}"
                                onclick="get_user_info(this)">
                            Hành động
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Chưa có người dùng nào</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $users->links('pagination::bootstrap-5') }}
</div>

<!-- The Modal -->
<div class="modal" id="changeInfoModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form action="" method="post" id="changeUserInfoForm">
            @csrf
            @method('put')
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Thay đổi trạng thái người dùng</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3 mt-3">
                    <label for="username" class="form-label">Tên người dùng:</label>
                    <input type="text" class="form-control" disabled
                            id="username" name="username" readonly value="Tên người dùng ở đây!!">
                </div>

                <div class="mb-3 mt-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" disabled readonly value="Email ở đây">
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <div class="mb-3 mt-3">
                    <label for="blocked_until" class="form-label">Ngày mở chặn</label>
                    <input type="datetime-local" class="form-control" id="blocked_until" name="blocked_until">
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="close_btn(this)">Close</button>
                <button type="submit" class="btn btn-success">Thay đổi</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script>
    const BASE_URL = "{{ url('/') }}";
</script>
<script src="{{ @asset('js/moderator/user_info.js') }}"></script>

@endsection