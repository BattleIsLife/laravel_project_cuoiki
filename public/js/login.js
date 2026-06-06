document.getElementById('loginForm').addEventListener('submit', function(e){
        // Không submit ngay, validate trước
        let isValid = true;

        // Reset lỗi cũ
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Lấy giá trị
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

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
            isValid = false;
            showError('password', 'Vui lòng nhập mật khẩu');
        }
        else if (password.length > 50) {
            isValid = false;
            showError('password', 'Độ dài của mật khẩu quá dài');
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