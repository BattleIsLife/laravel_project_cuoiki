<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FictionController;
use App\Http\Controllers\Author\AuthorFictionController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    // Trang chủ
    Route::get('/', 'index')->name('home');
    Route::get('/home', 'index');

    // Danh sách series mới nhất
    Route::get('/all_series', 'all_series')->name('all_series');
});

Route::controller(FictionController::class)->group(function(){
    // Danh sách và chi tiết truyện
    Route::get('/all_fictions', 'index')->name('all_fictions');
    Route::get('/fiction/{fictionId}', 'show')->name('fiction.detail');
});

// Route xử lý đăng nhập, đăng ký user
Route::controller(AuthController::class)->middleware('check.user.guest')->group(function(){
    // Trang đăng nhập
    Route::get('/login', 'showLogin')->name('user.login');
    Route::post('/login', 'login');

    // Trang đăng ký
    Route::get('/register', 'showRegister')->name('user.register');
    Route::post('/register', 'register');
});

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

    // Series
    Route::get('/series_list', [ProfileController::class, 'series_list'])->name('user.series_list');

    // Đăng xuất tài khoản
    Route::post('/logout', [AuthController::class, 'logout']);
});
