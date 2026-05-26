@extends('main')
@section('title', 'Đổi thông tin')
@section('content')
<div class="container p-4 mt-5">
<div class="container-sm card p-4">
    <form action="{{ url('/author/change_info') }}" method="post" id="changePasswordForm">
        @csrf
        @method('put')

        <h1 class="text-center">Thay đổi thông tin của {{ auth()->guard('web')->user()->username }}</h1>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0" style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="mb-3">
            <label for="new_password" class="form-label">Nhập mật khẩu mới:</label>
            <input type="password" class="form-control" 
                    id="new_password" placeholder="Nhập mật khẩu mới" name="new_password">
            <div class="invalid-feedback" id="new_passwordError"></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Nhập email mới:</label>
            <input type="email" class="form-control" 
                    id="email" placeholder="Nhập lại mật khẩu mới" name="email" value="{{ auth()->guard('web')->user()->email }}">
            <div class="invalid-feedback" id="emailError"></div>
        </div>

        <button type="submit" class="btn btn-success w-100" name="login">Đổi thông tin</button>
    </form>
</div>
</div>

<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function(e){
        // Không submit ngay, validate trước
        let isValid = true;

        // Reset lỗi cũ
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Lấy giá trị
        const new_password = document.getElementById('new_password').value;
        const email = document.getElementById('email').value;


        // Validate new password
        if (new_password === '') {
            isValid = false;
            showError('new_password', 'Vui lòng nhập mật khẩu mới');
        }

        else if (new_password.length > 50) {
            isValid = false;
            showError('password', 'Độ dài của mật khẩu quá dài');
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            showError('email', 'Vui lòng nhập email');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError('email', 'Email không hợp lệ');
            isValid = false;
        }


        if (!isValid) {
            e.preventDefault(); // Ngăn submit nếu có lỗi
        }   
        // Nếu OK thì form sẽ submit bình thường lên server
    });

    function showError(fieldId, message) {
        const errorEl = document.getElementById(fieldId + 'Error');
        const inputEl = document.getElementById(fieldId);
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        inputEl.classList.add('is-invalid');
    }
</script>
@endsection