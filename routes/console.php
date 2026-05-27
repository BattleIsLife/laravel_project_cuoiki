<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Tự động kiểm tra và unblock người dùng đã bị chặn
Schedule::call(function () {
    User::where('blocked_until', '<=', now())
        ->update(['blocked_until' => null]);
})->hourly();

