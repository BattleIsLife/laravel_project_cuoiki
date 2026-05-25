<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Fiction;
use App\Models\Series;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Top 8 truyện hot nhất theo lượt xem
        $hotFictions = Fiction::whereHas('chapters')->withSum('chapters', 'watch_count') // Tự động tính tổng cột 'views' trong bảng 'chapters'
                                ->orderBy('chapters_sum_watch_count', 'desc') // Sắp xếp theo thực thể vừa tính
                                ->take(8)
                                ->get();


        // Top 8 series hot nhất theo lượt xem
        $hotSeries = Series::whereHas('fictions')->withSum('chapters', 'watch_count') // Tự động tính tổng cột 'views' trong bảng 'chapters'
                            ->orderBy('chapters_sum_watch_count', 'desc') // Sắp xếp theo thực thể vừa tính
                            ->take(8)
                            ->get();

        // Top 5 bài viết mới nhất của moderator
        // $moderatorPosts = \App\Models\ModeratorPost::with('moderator')
        //     ->latest()
        //     ->limit(5)
        //     ->get();
        $data = [
            'hotFictions' => $hotFictions,
            'hotSeries'   => $hotSeries
        ];

        return view('user.home', $data);
    }

    public function all_fictions()
    {
        return view('fiction.all_fictions');
    }

    public function all_series()
    {
        return view('series.all_series');
    }

    // public function test()
    // {
    //     // $user = User::all()->first();
    //     // return view('test', ['user' => $user]);
    //     return view ('user.profile.list_series');
    // }
}
