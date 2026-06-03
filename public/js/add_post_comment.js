document.getElementById('commentForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Ngăn reload trang
    
    const formData = new FormData(this);

    const commentContent = document.getElementById('comment_content');
    const submitComment = document.getElementById('submit_comment');
    const commentText = commentContent.value;
    
    if (commentText.trim() === "") {
        alert('Vui lòng nhập nội dung bình luận');
        return;
    }

    if(commentText.length > 5000)
    {
        alert('Bình luận không được quá 5000 ký tự');
        return;   
    }

    const response = await fetch(`${BASE_URL}/moderator_posts/${POST_ID}/comments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            // Khi gửi form post bằng ajax: không có 'Content-Type': 'application/json',
        },
        body: formData
    });

    submitComment.disabled = true;
    const data = await response.json();

    // Không xử lý nếu bình luận thất bại
    if(!data)
    {
        return;
    }

    submitComment.disabled = false;
    commentContent.value = "";
    addCommentToList(data.comment);
});


// Hàm thêm bình luận mới vào list (tạo HTML động)
function addCommentToList(comment) {
    const list = document.getElementById('commentsList');
    const message = document.getElementById('no_comment_message');
    const commentHtml = `
        <div class="container-sm mt-2 p-3 border main-comment-wrapper comment-box" data-id="${comment.id}">
            <div class="border-bottom mb-2">
                <h6>${comment.username}</h6>
                <p><i>${comment.created_at}</i></p>
                <p>Tổng điểm: <span class="total-score">0</span></p>
            </div>
            <textarea class="form-control" disabled readonly required rows="6" >${comment.content}</textarea>
            <div class="mt-2 reply_comment"><button class="btn btn-primary" onclick="reply_comment(this, '${comment.id}')">Phản hồi bình luận</button></div>
            <div class="d-flex flex-row mt-2">
                <button class="btn btn-success" onclick="upvote_post_comment(this, 1)">Upvote</button>
                <button class="btn btn-danger" onclick="upvote_post_comment(this, -1)">Downvote</button>
                <button class="btn btn-secondary" 
                        data-bs-toggle="modal"
                        data-bs-target="#userDeleteCommentModal" 
                        onclick="user_delete_comment(this)">
                        Gỡ bình luận
                </button>
            </div>
            <div class="child_comment_list">

            </div>
        </div>
    `;

    // Xóa bỏ thông báo khi không có bình luận
    if(message) message.remove();

    list.insertAdjacentHTML('afterbegin', commentHtml); // Thêm vào đầu list
}


// Dưới đây là logic xử lý phản hồi bình luận
// Tạo form phản hồi bình luận
function reply_comment(btn, comment_id)
{
    const div_container = btn.closest('div.reply_comment');

    div_container.innerHTML = `
        <h4>Phản hồi bình luận</h4>
        <textarea class="form-control comment_content" rows="4"></textarea>
        <div class="d-flex flex-row">
            <button type="button" class="btn btn-success mt-3" onclick="add_child_to_comment(this, '${comment_id}')">Đăng tải bình luận</button>
            <button type="button" class="btn btn-danger mt-3" onclick="revert_back(this, '${comment_id}')">Hủy</button>
        </div>
    `;
}


// Trả lại nút cũ nếu hủy
function revert_back(btn, comment_id) {
    const parent = btn.closest('div').parentElement;
    parent.innerHTML = `<button class="btn btn-primary" onclick="reply_comment(this, '${comment_id}')">Phản hồi bình luận</button>`
}

// Xử lý thêm bình luận con
async function add_child_to_comment(btn, comment_id) {
    // alert('Not implimented');
    const div_container = btn.closest('div.reply_comment');
    const comment_content = div_container.querySelector('.comment_content');
    const commentText = comment_content.value;

    if (commentText.trim() === '')
    {
        alert('Vui lòng nhập nội dung bình luận');
        return;
    }

    if(commentText.length > 5000)
    {
        alert('Bình luận không được quá 5000 ký tự');
        return;   
    }

    // data gửi đi lên server
    const payload = {
        parent_comment: comment_id,
        content: commentText
    };

    const response = await fetch(`${BASE_URL}/moderator_posts/${POST_ID}/comments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    });

    // Lấy thẻ div chứa tất cả các nút và vô hiệu hóa trong quá trình gửi, tránh bấm nhiều hơn 1 lần
    const parent_div = btn.closest('.main-comment-wrapper');
    const all_btn = parent_div.querySelectorAll('button');
    for (let i = 0; i < all_btn.length; i++) {
        const element = all_btn[i];
        element.disabled = true;
    }

    const data = await response.json();

    if(!data)
        return;

    // Mở khóa nút
    for (let i = 0; i < all_btn.length; i++) {
        const element = all_btn[i];
        element.disabled = false;
    }

    const comment = data.comment;

    // Thêm bình luận con ở đầu danh sách
    const list = parent_div.querySelector('div.child_comment_list');
    const commentHtml = `
        <div class="container-sm mt-2 p-3 ps-5 border comment-box" data-id="${comment.id}">
            <div class="border-bottom mb-2">
                <h6>${comment.username}</h6>
                <p><i>${comment.created_at}</i></p>
                <p>Tổng điểm: <span class="total-score">0</span></p>
            </div>
            <textarea class="form-control" disabled readonly required rows="6" >${comment.content}</textarea>
            <div class="d-flex flex-row mt-2">
                <button class="btn btn-success" onclick="upvote_post_comment(this, 1)">Upvote</button>
                <button class="btn btn-danger" onclick="upvote_post_comment(this, -1)">Downvote</button>
                <button class="btn btn-secondary" 
                        data-bs-toggle="modal"
                        data-bs-target="#userDeleteCommentModal" 
                        onclick="user_delete_comment(this)">
                        Gỡ bình luận
                </button>
            </div>
        </div>
    `;

    list.insertAdjacentHTML('afterbegin', commentHtml); // Thêm vào đầu list

    // Trả lại nút cũ
    revert_back(btn, comment_id);
}