async function get_user_info(btn)
{
    const id = btn.getAttribute('data-id');
    
    const response = await fetch(`${BASE_URL}/admin/get_user_info/${id}`, {
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

    const user = data.user;

    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const blocked_until = document.getElementById('blocked_until');

    // Tạo date object để chuyển thời gian từ json thành datetime của js
    let dateObj = ''; 

    if(user.blocked_until)
    {
        dateObj = new Date(user.blocked_until);
        blocked_until.value = dateObj.toISOString().slice(0, 16); // Lấy các giá trị phù hợp cho form
    }

    username.value = user.username;
    email.value = user.email;
    
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', `${BASE_URL}/admin/user_block/${id}`);
    // console.log(form.getAttribute('action'));
}

function close_btn(btn) {
    const form = document.getElementById('changeUserInfoForm');
    form.setAttribute('action', '');
    // console.log(form.getAttribute('action'));
}