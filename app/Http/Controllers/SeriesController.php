<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    public function index()
    {
        $allSeries = Series::with('fictions')
            ->latest()
            ->paginate(10);

        return view('series.all_series', compact('allSeries'));
    }

    public function show(string $seriesId)
    {
        $series = Series::with('fictions')
            ->findOrFail($seriesId);

        $fictions = $series->fictions()
                    ->latest()
                    ->paginate(10);

        return view('series.detail_series', compact('series', 'fictions'));
    }
}
