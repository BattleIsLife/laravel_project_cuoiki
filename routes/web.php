<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Author\AuthorSeriesController;
use App\Http\Controllers\FictionController;
use App\Http\Controllers\Author\AuthorFictionController;
use App\Http\Controllers\Author\AuthorChapterController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    // Trang chủ
    Route::get('/', 'index')->name('home');
    Route::get('/home', 'index');

    // About me
    Route::get('/about_me', 'about_me')->name('about_me');
});


// Route xử lý truyện
Route::controller(FictionController::class)->group(function(){
    // Danh sách và chi tiết truyện
    Route::get('/all_fictions', 'index')->name('all_fictions');
    Route::get('/fiction/{fictionId}', 'show')->name('fiction.detail');
});


// Route xử lý series
Route::controller(SeriesController::class)->group(function(){
    Route::get('/all_series', 'index')->name('all_series');
    Route::get('/series/{seriesId}', 'show')->name('series.detail');
});


// Route xử lý chương
Route::get('/chapter/{chapterId}', [ChapterController::class, 'show']);


// Route xử lý đăng nhập, đăng ký user
Route::controller(AuthController::class)->middleware('check.user.guest')->group(function(){
    // Trang đăng nhập
    Route::get('/login', 'showLogin')->name('user.login');
    Route::post('/login', 'login');

    // Trang đăng ký
    Route::get('/register', 'showRegister')->name('user.register');
    Route::post('/register', 'register');
});


// Route tác giả
Route::prefix('author')->middleware('check.user.login')->group(function(){
    // Trang thông tin người dùng
    Route::get('/', [ProfileController::class, 'index'])->name('user.profile');
    Route::get('/profile', [ProfileController::class, 'index']);


    // Truyện
    Route::get('/fiction_list', [AuthorFictionController::class, 'index'])->name('user.fiction_list');
    Route::get('/new_fiction', [AuthorFictionController::class, 'create'])->name('user.new_fiction');
    Route::post('/new_fiction', [AuthorFictionController::class, 'store']);
    Route::get('/edit_fiction/{fictionId}', [AuthorFictionController::class, 'edit'])->name('user.edit_fiction');
    Route::post('/edit_fiction/{fictionId}', [AuthorFictionController::class, 'update']);
    Route::delete('/delete_fiction/{fictionId}', [AuthorFictionController::class, 'delete'])->name('user.delete_fiction');


    // Chương truyện
    // Thêm chương mới
    Route::get('/edit_fiction/{fictionId}/new_chapter', [AuthorChapterController::class, 'create']);
    Route::post('/edit_fiction/{fictionId}/new_chapter', [AuthorChapterController::class, 'store']);
    // Sửa chương
    Route::get('/edit_fiction/{fictionId}/edit_chapter/{chapterId}', [AuthorChapterController::class, 'edit']);
    Route::put('/edit_fiction/{fictionId}/edit_chapter/{chapterId}', [AuthorChapterController::class, 'update']);
    // Xóa chương
    Route::delete('/edit_fiction/{fictionId}/delete_chapter/{chapterId}', [AuthorChapterController::class, 'destroy']);


    // Series
    // Danh sách series
    Route::get('/series_list', [AuthorSeriesController::class, 'index'])->name('user.series_list');
    // Tạo series
    Route::get('/new_series', [AuthorSeriesController::class, 'create'])->name('user.new_series');
    Route::post('/new_series', [AuthorSeriesController::class, 'create_attempt']);
    // Sửa series
    Route::get('/edit_series/{id}', [AuthorSeriesController::class, 'edit']);
    Route::put('/edit_series/{id}', [AuthorSeriesController::class, 'edit_attempt']);
    // Thêm truyện vào series
    Route::post('/edit_series/{id}/add_fiction', [AuthorSeriesController::class, 'add_fiction_to_series']);
    // Xóa truyện khỏi series
    Route::delete('/edit_series/remove_fiction/{id}', [AuthorSeriesController::class, 'delete_fiction_from_series']);
    // Xóa series
    Route::delete('/delete_series/{id}', [AuthorSeriesController::class, 'delete']);


    // // Đăng xuất tài khoản
    Route::post('/logout', [AuthController::class, 'logout']);


    // Đổi thông tin
    Route::get('/change_info', [AuthController::class, 'change_info'])->name('user.change_info');
    Route::put('/change_info', [AuthController::class, 'change_info_attempt']);


    // Xóa tài khoản
    Route::get('delete_account', [AuthController::class, 'delete_account'])->name('user.delete_account');
    Route::delete('delete_account', [AuthController::class, 'delete_account_attempt']);
});
