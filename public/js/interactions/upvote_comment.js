async function upvote_comment(btn, value) {
    const id = btn.getAttribute('data-id');
    alert(`Bạn đã tương tác với bình luận ${id}: ${value}`);
}