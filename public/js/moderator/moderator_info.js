async function get_user_info(btn)
{
    const id = btn.getAttribute('data-id');

    const response = await fetch(`${BASE_URL}/admin/get_moderator_info/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Lỗi');
    }

    const moderator = data.moderator;

    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const permission = document.getElementById('permission');

    username.value = moderator.username;
    email.value = moderator.email;
    permission.value = moderator.permission;
    
    const change_info_form = document.getElementById('changeUserInfoForm');
    const toggle_delete_form = document.getElementById('toggleDeleteForm');
    const toggle_delete_btn = document.getElementById('toggleDeleteButton');
    change_info_form.setAttribute('action', `${BASE_URL}/admin/change_permission/${id}`);
    toggle_delete_form.setAttribute('action', `${BASE_URL}/admin/toggle_moderator/${id}`);
    if(moderator.deleted_at === null)
    {
        toggle_delete_btn.textContent = "Vô hiệu hóa tài khoản này!!";
        permission.disabled = false;
    }
    else
    {
        toggle_delete_btn.textContent = "Khôi phục tài khoản này!!";
        permission.disabled = true;
    }
}

function close_btn(btn) {
    const change_info_form = document.getElementById('changeUserInfoForm');
    const toggle_delete_form = document.getElementById('toggleDeleteForm');
    const toggle_delete_btn = document.getElementById('toggleDeleteButton');
    change_info_form.setAttribute('action', '');
    toggle_delete_form.setAttribute('action', '');
}