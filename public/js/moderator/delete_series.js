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

    // Khóa nút bấm tạm thời để tránh người dùng nhấn click đúp liên tục
    btn.disabled = true;

    const response = await fetch(`${BASE_URL}/admin/delete_series/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    const result = await response.json();

    if (response.ok && result.success) {
        // 5. Xóa phần tử trên giao diện (Khớp class cha của dòng series)
        const delete_button_with_id = document.querySelector(`a[data-id="${id}"]`);
        const div_tag = delete_button_with_id.closest('.series');
        div_tag.remove();

        // 6. Kích hoạt nút đóng Modal
        const closeBtn = document.getElementById('btn-close-modal');
        if (closeBtn) closeBtn.click();

        btn.disabled = false;
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

