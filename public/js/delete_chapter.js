function get_delete_id(btn) {
    const deleteStuff = document.getElementById('delete_stuff');
    const confirmDeleteButton = document.getElementById('confirm_delete_btn');
    const name = btn.getAttribute('data-name');
    const id = btn.getAttribute('data-id');

    confirmDeleteButton.setAttribute('data-id', id);
    deleteStuff.textContent = name;
}

async function confirm_delete(btn) {
    const id = btn.getAttribute('data-id');

    if (!id) {
        alert('Không tìm thấy chương truyện cần xóa.');
        return;
    }

    btn.disabled = true;
    btn.textContent = "Đang xóa...";

    try {
        const response = await fetch(`${BASE_URL}/author/edit_fiction/${FICTION_ID}/delete_chapter/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Xóa chương thất bại.');
        }

        const deleteButtonWithId = document.querySelector(`[data-id="${id}"]`);
        const chapterCard = deleteButtonWithId ? deleteButtonWithId.closest('.chapter') : null;

        if (chapterCard) {
            chapterCard.remove();
        }

        const closeBtn = document.getElementById('btn-close-modal');
        if (closeBtn) {
            closeBtn.click();
        }
    } catch (error) {
        alert(error.message);
    } finally {
        btn.disabled = false;
    }
}
