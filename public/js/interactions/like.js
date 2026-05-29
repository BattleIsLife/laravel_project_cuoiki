async function like_fiction(btn) {

    const fiction_id = btn.getAttribute('data-bs-id');

    // Fetch API
    const response = await fetch(`${BASE_URL}/fiction/${fiction_id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    btn.disabled = true;
    const data = await response.json();

    // Không xử lý nếu như like thất bại
    if(!data)
    {
        return;
    }

    btn.disabled = false;
    // Còn lại thì update số lượt like
    document.getElementById('likeCount').textContent = data.like_count
}

async function like_fiction_in_series(btn) {

    const fiction_id = btn.getAttribute('data-bs-id');

    // alert('Bạn vừa like chương: ' + fiction_id);
    // Fetch API
    const response = await fetch(`${BASE_URL}/fiction/${fiction_id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    btn.disabled = true;
    const data = await response.json();

    // Không xử lý nếu như like thất bại
    if(!data)
    {
        return;
    }

    // Còn lại thì update số lượt like
    const cardContainer = btn.closest('.card');
    
    if (cardContainer) {
        // 2. Từ thẻ cha chung, tìm đi xuống phần tử hiển thị số lượt thích của riêng chương này
        const like_count_el = cardContainer.querySelector('.likeFictionCount');
        
        if (like_count_el) {
            // Cập nhật số lượt thích mới (Hãy chắc chắn Controller trả về đúng key, ví dụ: data.like_count)
            btn.disabled = false;
            like_count_el.innerText = data.like_count;
        }
    }
}


async function like_chapter(btn) {

    const chapter_id = btn.getAttribute('data-bs-id');

    // alert('Bạn vừa like chương: ' + chapter_id);
    // Fetch API
    const response = await fetch(`${BASE_URL}/chapter/${chapter_id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    });

    btn.disabled = true;
    const data = await response.json();

    // Không xử lý nếu như like thất bại
    if(!data)
    {
        return;
    }

    // Còn lại thì update số lượt like
    const cardContainer = btn.closest('.card');
    
    if (cardContainer) {
        // 2. Từ thẻ cha chung, tìm đi xuống phần tử hiển thị số lượt thích của riêng chương này
        const like_count_el = cardContainer.querySelector('.likeChapterCount');
        
        if (like_count_el) {
            // Cập nhật số lượt thích mới (Hãy chắc chắn Controller trả về đúng key, ví dụ: data.like_count)
            btn.disabled = false;
            like_count_el.innerText = data.like_count;
        }
    }
}