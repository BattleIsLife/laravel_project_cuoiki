@extends('admin.profile')
@section('title')
    Quản lý quản trị viên
@endsection
@section('moderator_profile_component')
<div class="text-center">
    <h2>Danh sách quản trị viên</h2>
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
<form class="d-flex" role="search" method="get" action="{{ route('admin.moderator_list') }}">
    <input class="form-control me-2" type="search" name="q" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm tên quản trị viên, email" aria-label="Search">
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
                <th>Vai trò</th>
                <th>Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            @php
                $stt = 1;
            @endphp
            @forelse ($moderators as $mod)
                <tr>
                    <td>{{ $stt }}</td>
                    <td>{{ $mod->username }}</td>
                    <td>{{ $mod->email }}</td>
                    <td>{{ $mod->created_at }}</td>
                    @php
                        $stt++;
                    @endphp
                    <td>{{ $mod->permission_name }}</td>
                    <td><button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#changeInfoModal"
                                data-id="{{ $mod->id }}"
                                onclick="get_user_info(this)"
                                @if ($mod->permission === 'admin') disabled @endif>
                            Hành động
                        </button></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Chưa có quản trị viên nào</td></tr>
            @endforelse
        </tbody>
    </table>
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
            <h4 class="modal-title">Tùy chọn moderator</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3 mt-3">
                    <label for="username" class="form-label">Tên người dùng:</label>
                    <input type="text" class="form-control" disabled
                            id="username" name="username" readonly value="">
                </div>

                <div class="mb-3 mt-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" disabled readonly value="">
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <div class="mb-3 mt-3">
                    <label for="blocked_until" class="form-label">Vai trò</label>
                    <select class="form-select" id="permission" name="permission">
                        <option value="none" selected>Không có</option>
                        <option value="user_moderator">Quản trị người dùng</option>
                        <option value="post_moderator">Quản trị bài đăng</option>
                    </select>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="close_btn(this)">Close</button>
                <button type="submit" class="btn btn-success" id="changingPermission">Thay đổi</button>
        </form>
                <form action="" method="post" id="toggleDeleteForm">
                    @csrf
                    <button type="submit" class="btn btn-secondary" id="toggleDeleteButton"></button>
                </form>
            </div>
        
    </div>
  </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $moderators->links('pagination::bootstrap-5') }}
</div>

<script>
    const BASE_URL = "{{ url('/') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}"
</script>
<script src="{{ @asset('js/moderator/moderator_info.js') }}"></script>
@endsection