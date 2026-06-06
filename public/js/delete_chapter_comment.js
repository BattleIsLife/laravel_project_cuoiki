function user_delete_comment(btn) {
    // Lấy comment_id
    const comment_box = btn.closest('div.comment-box');
    const comment_id = comment_box.getAttribute('data-id');

    // Lấy nút xác nhận xóa
    const delete_button = document.getElementById('user_confirm_delete_comment');
    delete_button.setAttribute('data-id', comment_id);

}

async function user_confirm_delete_comment(btn) {
    // Lấy comment_id
    const comment_id = btn.getAttribute('data-id');

    // Láy nút xóa để đóng
    const close_button = document.getElementById('close_user_delete_comment_modal');

    const comment_box = document.querySelector(`div[data-id="${comment_id}"]`);

    btn.disabled = true;
    close_button.disabled = true;

    const response = await fetch(`${BASE_URL}/chapter_comments/${comment_id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    const data = await response.json();

    // Không xử lý nếu như like thất bại
    if(!data)
    {
        return;
    }

    btn.disabled = false;
    close_button.disabled = false;

    comment_box.remove();

    close_button.click();
}

function user_close_button(btn) {
    // Lấy nút xác nhận xóa để gỡ attribute
    const delete_button = document.getElementById('user_confirm_delete_comment');
    
    delete_button.removeAttribute('data-id');
}