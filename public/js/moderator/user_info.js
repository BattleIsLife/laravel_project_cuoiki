function get_user_info(btn)
{
    const userid_value = btn.getAttribute('data-id');
    const username_value = btn.getAttribute('data-username');
    const email_value = btn.getAttribute('data-email');
    const blocked_until_value = btn.getAttribute('data-blocked-until');

    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const blocked_until = document.getElementById('blocked_until');

    username.value = username_value;
    email.value = email_value;
    blocked_until.value = blocked_until_value;
    
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', `${BASE_URL}/admin/user_block/${userid_value}`);
    // console.log(form.getAttribute('action'));
}

function close_btn(btn) {
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', '');
    // console.log(form.getAttribute('action'));
}