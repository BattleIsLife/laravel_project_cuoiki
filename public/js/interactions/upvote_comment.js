async function upvote_comment(btn, value) {
    const comment_id = btn.getAttribute('data-id');
    // alert(`Bạn đã tương tác với bình luận ${id}: ${value}`);

    const payload = {
        vote: value
    };
    // Fetch API
    const response = await fetch(`${BASE_URL}/chapter_comments/${comment_id}/vote`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    });

    const parent_div = btn.parentElement;
    const all_btn = parent_div.children;

    for (let i = 0; i < all_btn.length; i++) {
        const button = all_btn[i];
        button.disabled = true;   
    }

    const data = await response.json();

    // Không xử lý nếu như like thất bại
    if(!data)
    {
        return;
    }

    for (let i = 0; i < all_btn.length; i++) {
        const button = all_btn[i];
        button.disabled = false;   
    }

    const commentBox = btn.closest('.comment-box');
    const scoreElement = commentBox.querySelector('.total-score');
    scoreElement.innerText = data.total_vote;
}