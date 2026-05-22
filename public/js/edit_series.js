document.getElementById('seriesForm').addEventListener('submit', function(e){
        // Không submit ngay, validate trước
        let isValid = true;

        // Reset lỗi cũ
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Lấy giá trị
        const series_name = document.getElementById('series_name').value;

        // Validate series_name
        if (series_name === '') {
            isValid = false;
            showError('series_name', 'Vui lòng nhập tên series');
        }


        if (!isValid) {
            e.preventDefault(); // Ngăn submit nếu có lỗi
        }   
        // Nếu OK thì form sẽ submit bình thường lên server
    });

    function showError(fieldId, message) {
        const errorEl = document.getElementById(fieldId + '_Error');
        const inputEl = document.getElementById(fieldId);
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        inputEl.classList.add('is-invalid');
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('previewImg');
        const previewContainer = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                // previewContainer.style.display = 'block'; // Hiện preview
            }

            reader.readAsDataURL(input.files[0]); // Đọc file thành base64 để hiển thị
        } else {
            preview.src = '#';
            // previewContainer.style.display = 'none'; // Ẩn nếu không có file
        }
    }