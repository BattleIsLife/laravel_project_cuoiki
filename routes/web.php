<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Author\AuthorChapterController;
Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);

Route::get('/test', function(){
    return view('test');
});
// ============================================================
// PUBLIC - ChapterController
// Không cần đăng nhập để đọc, like/comment cần auth
// ============================================================
 
// Xem nội dung chương
Route::get('/fictions/{fictionId}/chapters/{chapterId}', [ChapterController::class, 'show'])
    ->name('chapters.show');
 
// AJAX: Tăng lượt xem (gọi sau 12 giây)
Route::post('/chapters/{chapterId}/watch', [ChapterController::class, 'incrementWatch'])
    ->name('chapters.watch');
 
// AJAX: Like / Unlike chương
Route::post('/chapters/{chapterId}/like', [ChapterController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('chapters.like');
 
// AJAX: Gửi bình luận
Route::post('/chapters/{chapterId}/comments', [ChapterController::class, 'storeComment'])
    ->middleware('auth')
    ->name('chapter-comments.store');
 
// AJAX: Upvote bình luận
Route::post('/chapter-comments/{commentId}/vote', [ChapterController::class, 'voteComment'])
    ->middleware('auth')
    ->name('chapter-comments.vote');
 
// AJAX: Xóa bình luận (chủ comment)
Route::delete('/chapter-comments/{commentId}', [ChapterController::class, 'destroyComment'])
    ->middleware('auth')
    ->name('chapter-comments.destroy');
 
// ============================================================
// AUTHOR - AuthorChapterController
// Tất cả route yêu cầu đăng nhập
// ============================================================
Route::middleware('auth')->prefix('author')->name('author.')->group(function () {
 
    // Danh sách chương của một truyện
    Route::get('/fictions/{fictionId}/chapters', [AuthorChapterController::class, 'index'])
        ->name('chapters.index');
 
    // Form tạo chương mới
    Route::get('/fictions/{fictionId}/chapters/create', [AuthorChapterController::class, 'create'])
        ->name('chapters.create');
 
    // Lưu chương mới
    Route::post('/fictions/{fictionId}/chapters', [AuthorChapterController::class, 'store'])
        ->name('chapters.store');
 
    // Form sửa chương
    Route::get('/fictions/{fictionId}/chapters/{chapterId}/edit', [AuthorChapterController::class, 'edit'])
        ->name('chapters.edit');
 
    // Cập nhật chương
    Route::put('/fictions/{fictionId}/chapters/{chapterId}', [AuthorChapterController::class, 'update'])
        ->name('chapters.update');
 
    // AJAX: Xóa chương
    Route::delete('/fictions/{fictionId}/chapters/{chapterId}', [AuthorChapterController::class, 'destroy'])
        ->name('chapters.destroy');
 
    // AJAX: Toggle draft / đã đăng
    Route::post('/fictions/{fictionId}/chapters/{chapterId}/toggle', [AuthorChapterController::class, 'togglePublish'])
        ->name('chapters.toggle');
});
