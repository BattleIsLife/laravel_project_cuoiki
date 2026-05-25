<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Fiction;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.account_info');
    }

    public function fiction_list()
    {
        $fictions = Fiction::whereUserId(Auth::guard('web')->user()->id)->withCount('like_fiction_history')
                            ->orderByDesc('created_at')
                            ->paginate(10);

        $data = [
            'fictions' => $fictions,
        ];

        return view('user.profile.list_fictions', $data);
    }

    public function series_list()
    {
        $series = Series::whereUserId(Auth::guard('web')->user()->id)->orderByDesc('created_at')->get();

        $data = [
            'series' => $series
        ];

        return view('user.profile.list_series', $data);
    }
}
