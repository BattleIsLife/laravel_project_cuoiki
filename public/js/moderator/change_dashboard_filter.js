document.addEventListener('DOMContentLoaded', function () {
    const filterSelect = document.getElementById('filterSelect');
    const customDateRange = document.getElementById('customDateRange');
    const filterForm = filterSelect ? filterSelect.closest('form') : null;

    if (filterSelect && customDateRange) {
        
        // Hàm xử lý ẩn hiện dựa trên giá trị đang được chọn
        function toggleDateInputs() {
            if (filterSelect.value === 'custom') {
                // Hiện form chọn ngày dưới dạng flex của Bootstrap
                customDateRange.classList.remove('d-none');
                customDateRange.classList.add('d-flex');
                customDateRange.style.setProperty('display', 'flex', 'important');
            } else {
                // Ẩn form chọn ngày đi
                customDateRange.classList.remove('d-flex');
                customDateRange.classList.add('d-none');
                customDateRange.style.setProperty('display', 'none', 'important');
            }
        }

        // Chạy lần đầu tiên khi trang vừa tải xong để giữ đúng trạng thái cũ
        toggleDateInputs();

        // Lắng nghe sự kiện thay đổi lựa chọn của Admin
        filterSelect.addEventListener('change', function () {
            toggleDateInputs();
        });
    }
});