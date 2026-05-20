document.getElementById('registerForm').addEventListener('submit', function(e) {
    // Không submit ngay, validate trước
    let isValid = true;

    // Reset lỗi cũ
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

    // Lấy giá trị
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    // Validate username
    if (username === '') {
        showError('username', 'Vui lòng nhập tên người dùng');
        isValid = false;
    } else if (username.length < 3 || username.length > 50) {
        showError('username', 'Tên người dùng phải từ 3-50 ký tự');
        isValid = false;
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

    // Validate password
    if (password === '') {
        showError('password', 'Vui lòng nhập mật khẩu');
        isValid = false;
    } else if (password.length < 6) {
        showError('password', 'Mật khẩu phải ít nhất 6 ký tự');
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