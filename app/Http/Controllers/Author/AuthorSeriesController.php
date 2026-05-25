<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Support\Facades\Auth;

class AuthorSeriesController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $allSeries = Series::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('user.profile.list_series', compact('allSeries'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        return view('series.new_series');
    }

    public function show($seriesId)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $series = Series::with(['author', 'fictions'])
            ->where('id', $seriesId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $fictions = $series->fictions()
            ->latest()
            ->paginate(20);

        return view('series.detail_series', compact('series', 'fictions'));
    }

    public function edit($seriesId)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $series = Series::where('id', $seriesId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('series.edit_series', compact('series'));
    }

    public function delete($seriesId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để truy cập.',
            ], 401);
        }

        $series = Series::where('id', $seriesId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $series->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa series.',
        ]);
    }
}
