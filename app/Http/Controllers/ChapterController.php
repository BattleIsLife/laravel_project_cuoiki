<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Fiction;
use App\Models\ChapterComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    // =========================================================
    // [PUBLIC] Xem nội dung một chương để đọc
    // Route: GET /chapters/{chapterId}
    // View : chapter/detail_chapters
    // =========================================================
    public function show(string $chapterId)
    {
        $user = Auth::guard('web')->user();
        $moderator = Auth::guard('moderator')->user();
        $chapter = Chapter::whereId($chapterId)
            ->where('is_posted', 1)
            ->firstOrFail();

        // Bình luận gốc của chương (phân trang 15 cái/trang)
        $comments = ChapterComment::with('user')
            ->with('child_comment', function ($child_cmt) {
                $child_cmt->withSum('upvote_history as total_score', 'count')->latest()->paginate(10);
            })
            ->withSum('upvote_history as total_score', 'count')
            ->where('chapter_id', $chapterId)
            ->whereNull('parent_comment')
            ->latest()
            ->paginate(15);

        // Tăng lượt xem nếu người click vào không phải là chủ truyện hoặc đã đăng nhập user

        if($user)
            if($user->id !== $chapter->fiction->user_id)
            {
                $chapter->timestamps = false;
                $chapter->watch_count += 1;
                $chapter->save();
            }
        

        $data = [
            'chapter' => $chapter,
            'comments' => $comments,
            'user' => $user,
            'moderator' => $moderator
        ];

        return view('chapter.detail_chapters', $data);
    }
}