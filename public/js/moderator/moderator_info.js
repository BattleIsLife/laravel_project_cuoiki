function get_user_info(btn)
{
    const userid_value = btn.getAttribute('data-id');
    const username_value = btn.getAttribute('data-username');
    const email_value = btn.getAttribute('data-email');
    const permission_value = btn.getAttribute('data-permission');

    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const permission = document.getElementById('permission');

    username.value = username_value;
    email.value = email_value;
    permission.value = permission_value;
    
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', `${BASE_URL}/admin/change_permission/${userid_value}`);
    // console.log(form.getAttribute('action'));
}

function close_btn(btn) {
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', '');
    // console.log(form.getAttribute('action'));
}