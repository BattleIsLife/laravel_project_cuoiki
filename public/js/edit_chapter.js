document.getElementById('editChapterForm').addEventListener('submit', function(e){
    // Không submit ngay, validate trước
    let isValid = true;

    // Reset lỗi cũ
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });

    // Lấy giá trị
    const title = document.getElementById('title').value;

    // Validate chapter_title
    if (title === '') {
        isValid = false;
        showError('title', 'Vui lòng nhập tên chapter');
    }


    if (!isValid) {
        e.preventDefault(); // Ngăn submit nếu có lỗi
    }   
    // Nếu OK thì form sẽ submit bình thường lên server
});