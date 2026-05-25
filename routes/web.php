<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    // Trang chủ
    Route::get('/', 'index')->name('home');
    Route::get('/home', 'index');

    // Danh sách fiction, series mới nhất
    Route::get('/all_fictions', 'all_fictions')->name('all_fictions');
    Route::get('/all_series', 'all_series')->name('all_series');
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
    // Trang danh sách truyện
    Route::get('/fiction_list', [ProfileController::class, 'fiction_list'])->name('user.fiction_list');

    // // Thêm truyện mới
    // Route::get('/new_fiction', [AuthorFictionController::class, 'new_fiction'])->name('user.new_fiction');
    // Route::post('/new_fiction', [AuthorFictionController::class, 'new_fiction_attempt']);

    // // Sửa truyện
    // Route::get('/edit_fiction/{id}', [AuthorFictionController::class, 'edit_fiction']);
    // Route::post('/edit_fiction/{id}', [AuthorFictionController::class, 'edit_fiction_attempt']);


    // Series
    // Danh sách series
    Route::get('/series_list', [ProfileController::class, 'series_list'])->name('user.series_list');


    // // Đăng xuất tài khoản
    Route::post('/logout', [AuthController::class, 'logout']);
});
