document.getElementById('loginForm').addEventListener('submit', function(e){
        // Không submit ngay, validate trước
        let isValid = true;

        // Reset lỗi cũ
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Lấy giá trị
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Validate username
        if (username === '') {
            isValid = false;
            showError('username', 'Vui lòng nhập tên người dùng');
        }
        else if (username.length > 50) {
            isValid = false;
            showError('username', 'Độ dài của tên người dùng quá dài');
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