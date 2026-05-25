function get_delete_id(btn) {
    const deleteStuff = document.getElementById('delete_stuff');
    const confirm_delete_button = document.getElementById('confirm_delete_btn');
    const name = btn.getAttribute('data-name');
    const id = btn.getAttribute('data-id')

    confirm_delete_button.setAttribute('data-id', id);
    deleteStuff.textContent = name;
}

async function confirm_delete(btn) {
    const id = btn.getAttribute('data-id');
    alert('Đã xóa ' + id);

    const delete_button_with_id = document.querySelector(`a[data-id="${id}"]`);
    const div_tag = delete_button_with_id.closest('.fiction');
    div_tag.remove();

    const closeBtn = document.getElementById('btn-close-modal');
    closeBtn.click();
}

