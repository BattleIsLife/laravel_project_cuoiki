<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Author\AuthorSeriesController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/author/series', [AuthorSeriesController::class, 'index'])->name('author.series.index');
Route::get('/author/series/create', [AuthorSeriesController::class, 'create'])->name('author.series.create');
Route::get('/author/series/{seriesId}', [AuthorSeriesController::class, 'show'])->name('author.series.show');
Route::get('/author/series/{seriesId}/edit', [AuthorSeriesController::class, 'edit'])->name('author.series.edit');
Route::delete('/author/series/{seriesId}', [AuthorSeriesController::class, 'delete'])->name('author.series.delete');

Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
Route::get('/series/{seriesId}', [SeriesController::class, 'show'])->name('series.show');
Route::get('/series/{seriesId}/edit', [SeriesController::class, 'edit'])->name('series.edit');
Route::delete('/series/{seriesId}', [SeriesController::class, 'delete'])->name('series.delete');

// Route::get('/test', [HomeController::class, 'test'])->name('test');
