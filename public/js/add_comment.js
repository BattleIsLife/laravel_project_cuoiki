let logged_in = false;

// Kiểm tra đăng nhập
// document.addEventListener('DOMContentLoaded', function () {
//     fetch("")
//         .then(response => response.json())
//         .then(data => {
//             if(data.logged_in)
//             {
//                logged_in = true;
//             }
//         })
//         .catch(error => {
//             console.error('Lỗi kiểm tra đăng nhập:', error);
//         });
// });

document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Ngăn reload trang
    
    const formData = new FormData(this);

    const commentContent = document.getElementById('comment_content');
    const commentText = commentContent.value;

    if(!logged_in)
    {
        alert('Vui lòng đăng nhập để bình luận');
        return;
    }
    
    if (commentText.trim() === "") {
        alert('Vui lòng nhập nội dung bình luận');
        return;
    }

    // addCommentToList(commentText);

    fetch('', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Để CI4 nhận biết AJAX
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Thêm bình luận mới vào list (không reload)
            addCommentToList(data.comment);
            // Xóa textarea
            commentContent.value= "";
            // alert('Bình luận đã được đăng!');
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể đăng bình luận'));
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Có lỗi xảy ra khi đăng bình luận');
    });
});


// Hàm thêm bình luận mới vào list (tạo HTML động)
function addCommentToList(comment) {
    const list = document.getElementById('commentsList');
    const commentHtml = `
        <div class="container-sm mt-2 p-3 border">
            <div class="border-bottom mb-2">
                <h6>${comment.username || 'Người dùng'}</h6>
                <p><i>${comment.created_date}</i></p>
            </div>
            <textarea class="form-control" disabled readonly rows="6">
${comment.content}
            </textarea>
        </div>
    `;

    list.insertAdjacentHTML('afterbegin', commentHtml); // Thêm vào đầu list
}