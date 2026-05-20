<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Author\AuthorChapterController;
Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);

Route::get('/test', [HomeController::class, 'test']);
