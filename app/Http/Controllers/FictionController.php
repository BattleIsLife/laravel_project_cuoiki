<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Fiction;
use Illuminate\Http\Request;

class FictionController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $fictions = Fiction::with(['author', 'series'])->whereHas('chapters')
            ->withCount('like_fiction_history')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($inner) use ($keyword) {
                    $inner->where('fiction_name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('author', function ($authorQuery) use ($keyword) {
                            $authorQuery->where('username', 'like', "%{$keyword}%");
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('fiction.all_fictions', compact('fictions', 'keyword'));
    }

    public function show($fictionId)
    {
        $fiction = Fiction::with(['author', 'series'])
            ->withCount('like_fiction_history')
            ->findOrFail($fictionId);

        $chapters = Chapter::where('fiction_id', $fictionId)
            ->where('is_posted', 1)
            ->orderBy('chapter_order')
            ->get();

        return view('fiction.detail_fictions', compact('fiction', 'chapters'));
    }
}
