<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    public function index()
    {
        $allSeries = Series::with('author')
            ->latest()
            ->paginate(20);

        return view('series.all_series', compact('allSeries'));
    }

    public function show($seriesId)
    {
        $series = Series::with(['author', 'fictions'])
            ->findOrFail($seriesId);

        $fictions = $series->fictions()
            ->latest()
            ->paginate(20);

        return view('series.detail_series', compact('series', 'fictions'));
    }

    public function create()
    {
        return view('series.new_series');
    }

    public function edit($seriesId)
    {
        $series = Series::where('id', $seriesId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('series.edit_series', compact('series'));
    }

    public function delete($seriesId)
    {
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
