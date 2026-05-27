<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    public function index(Request $request)
    {

        $keyword = trim((string) $request->query('q', ''));

        // Chỉ hiển thị các series mà có ít nhất 1 truyện đăng chương
        $allSeries = Series::with('author')
            ->whereHas('fictions', function ($fiction) {
                $fiction->whereHas('chapters', function ($chapter) {
                    $chapter->where('is_posted', '=', 1);
                });
            })
            ->when($keyword !== '', function($query) use ($keyword){
                $query->where('series_name', 'like', "%{$keyword}%")
                      ->orWhereHas('author', function ($authorQuery) use ($keyword) {
                        $authorQuery->where('username', 'like', "%{$keyword}%");
                    });
            })
            ->latest()
            ->paginate(10)->withQueryString();

        return view('series.all_series', compact('allSeries', 'keyword'));
    }

    public function show(string $seriesId)
    {
        $series = Series::whereHas('fictions', function ($fiction) {
                $fiction->whereHas('chapters', function ($chapter) {
                    $chapter->where('is_posted', '=', 1);
                });
            })
            ->findOrFail($seriesId);

        $fictions = $series->fictions()->whereHas('chapters', function ($chapter) {
                    $chapter->where('is_posted', '=', 1);
                })
                ->withCount('like_fiction_history')
                ->latest()
                ->paginate(10);

        return view('series.detail_series', compact('series', 'fictions'));
    }
}
