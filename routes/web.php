<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);

Route::get('/test', function(){
    return view('test');
});
