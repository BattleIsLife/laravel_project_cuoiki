document.getElementById('addFictionToSeriesForm').addEventListener('submit', function(e){
        // Không submit ngay, validate trước
        let isValid = true;

        // Reset lỗi cũ
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Lấy giá trị
        const fiction_id = document.getElementById('selectFiction').value;

        // Validate fiction_id
        if (fiction_id === '') {
            // alert('not triggered')
            isValid = false;
            showError('selectFiction', 'Vui lòng chọn 1 truyện');
        }


        if (!isValid) {
            e.preventDefault(); // Ngăn submit nếu có lỗi
        }   
        // alert('triggered')
        // Nếu OK thì form sẽ submit bình thường lên server
    });

function showError(fieldId, message) {
    const errorEl = document.getElementById(fieldId + '_Error');
    const inputEl = document.getElementById(fieldId);
    errorEl.textContent = message;
    errorEl.style.display = 'block';
    inputEl.classList.add('is-invalid');
}