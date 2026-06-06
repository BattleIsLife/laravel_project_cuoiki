function get_delete_id(btn) {
    const deleteStuff = document.getElementById('delete_stuff');
    const confirm_delete_button = document.getElementById('confirm_delete_btn');
    const name = btn.getAttribute('data-name');
    const id = btn.getAttribute('data-id')

    confirm_delete_button.setAttribute('data-id', id);
    deleteStuff.textContent = name;
}

async function confirm_delete(btn) {
    // 1. Lấy ID của phần tử cần xóa lưu trên nút xác nhận
    const id = btn.getAttribute('data-id');
    
    // 2. Lấy mã CSRF Token từ thẻ meta đã chuẩn bị ở trên
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Khóa nút bấm tạm thời để tránh người dùng nhấn click đúp liên tục
    btn.disabled = true;
    btn.textContent = 'Đang xóa...';

    try {
        // 3. Thực hiện gọi AJAX ngầm lên Server
        const response = await fetch(`${BASE_URL}/author/delete_series/${id}`, {
            method: 'DELETE', // Hoặc 'DELETE' tùy bạn cấu hình trong web.php
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // 🔑 Bắt buộc phải có dòng này để Laravel thông qua
                'X-Requested-With': 'XMLHttpRequest' // Khai báo cho Laravel biết đây là request AJAX
            }
        });

        // 4. Giải mã dữ liệu JSON trả về từ Controller
        const result = await response.json();

        if (response.ok && result.success) {

            // 5. Xóa phần tử trên giao diện (Khớp class cha của dòng series, ví dụ '.series-item' hoặc '.fiction')
            const delete_button_with_id = document.querySelector(`a[data-id="${id}"]`);
            const div_tag = delete_button_with_id.closest('.series');
            div_tag.remove();

            // 6. Kích hoạt nút đóng Modal
            const closeBtn = document.getElementById('btn-close-modal');
            if (closeBtn) closeBtn.click();

        } else {
            // Nếu Server trả về lỗi (Ví dụ: 401 chưa đăng nhập, hoặc success = false)
            alert('Lỗi: ' + (result.message || 'Không thể xóa series này.'));
        }

    } catch (error) {
        // Xử lý nếu mất mạng hoặc lỗi server sập hệ thống
        console.error('Đã xảy ra lỗi hệ thống:', error);
        alert('Có lỗi xảy ra trong quá trình xử lý, vui lòng thử lại.');
    } finally {
        // Mở khóa lại nút bấm sau khi xử lý xong xuôi
        btn.disabled = false;
        btn.textContent = 'Xác nhận xóa';
    }
}

// async function confirm_delete(btn) {
//     const id = btn.getAttribute('data-id');
//     alert('Đã xóa ' + id);

//     const delete_button_with_id = document.querySelector(`a[data-id="${id}"]`);
//     const div_tag = delete_button_with_id.closest('.series');
//     div_tag.remove();

//     const closeBtn = document.getElementById('btn-close-modal');
//     closeBtn.click();
// }

