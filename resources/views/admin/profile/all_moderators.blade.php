@extends('admin.profile')
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
            @forelse ($moderators as $moderator)
                <tr>
                    <td>{{ $stt }}</td>
                    <td>{{ $moderator->username }}</td>
                    <td>{{ $moderator->email }}</td>
                    <td>{{ $moderator->created_at }}</td>
                    @php
                        $stt++;
                        // Tính toán quyền hạn
                        $permission = $moderator->permission;
                        $permission_name = "";
                        $permission_level = 0;
                        switch ($permission) {
                            case 'admin':
                                $permission_name = 'Admin';
                                break;
                            
                            case 'user_moderator':
                                $permission_name = 'Quản trị người dùng';
                                break;

                            case 'post_moderator':
                                $permission_name = 'Quản trị bài đăng';
                                break;
                            default:
                                $permission_name = 'Không có';
                        }
                    @endphp
                    <td>{{ $permission_name }}</td>
                    <td>Nút bấm sẽ ở đây!!</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Chưa có quản trị viên nào</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $moderators->links('pagination::bootstrap-5') }}
</div>
@endsection