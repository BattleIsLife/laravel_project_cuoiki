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
        $user = Auth::guard('web')->user();

        if($user)
            if($user->id !== $chapter->fiction->user_id)
            {
                $chapter->timestamps = false;
                $chapter->watch_count += 1;
                $chapter->save();
            }
        

        $data = [
            'chapter' => $chapter,
            'comments' => $comments
        ];

        return view('chapter.detail_chapters', $data);
    }

    // =========================================================
    // [AJAX] Tăng lượt xem - gọi sau 12 giây user ở lại trang
    // Route: POST /chapters/{chapterId}/watch
    // =========================================================
    // public function incrementWatch($chapterId)
    // {
    //     $chapter = Chapter::where('id', $chapterId)
    //         ->where('is_posted', 1)
    //         ->firstOrFail();

    //     $chapter->increment('watch_count');

    //     return response()->json([
    //         'success'     => true,
    //         'watch_count' => $chapter->watch_count,
    //     ]);
    // }

    // // =========================================================
    // // [AJAX] Like / Unlike chương (cần đăng nhập)
    // // Route: POST /chapters/{chapterId}/like
    // // =========================================================
    // public function toggleLike($chapterId)
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
    //     }

    //     // FIX: kiểm tra user bị block không cho like
    //     $user = Auth::user();
    //     if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tài khoản của bạn bị chặn đến ' . $user->blocked_until->format('d/m/Y H:i') . '.',
    //         ], 403);
    //     }

    //     Chapter::where('id', $chapterId)->where('is_posted', 1)->firstOrFail();

    //     $existing = DB::table('like_chapter_history')
    //         ->where('chapter_id', $chapterId)
    //         ->where('user_id', Auth::id())
    //         ->first();

    //     if ($existing) {
    //         DB::table('like_chapter_history')
    //             ->where('chapter_id', $chapterId)
    //             ->where('user_id', Auth::id())
    //             ->delete();
    //         $liked = false;
    //     } else {
    //         DB::table('like_chapter_history')->insert([
    //             'chapter_id'  => $chapterId,
    //             'user_id'     => Auth::id(),
    //             'created_at'  => now(),
    //             'updated_at'  => now(),
    //         ]);
    //         $liked = true;
    //     }

    //     $likeCount = DB::table('like_chapter_history')
    //         ->where('chapter_id', $chapterId)
    //         ->count();

    //     return response()->json([
    //         'success'    => true,
    //         'liked'      => $liked,
    //         'like_count' => $likeCount,
    //     ]);
    // }

    // // =========================================================
    // // [AJAX] Gửi bình luận mới (cần đăng nhập)
    // // Route: POST /chapters/{chapterId}/comments
    // // =========================================================
    // public function storeComment(Request $request, $chapterId)
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để bình luận.'], 401);
    //     }

    //     $request->validate([
    //         'content'        => 'required|string|max:1000',
    //         'parent_comment' => 'nullable|exists:chapter_comments,id',
    //     ]);

    //     // Kiểm tra tài khoản bị block
    //     $user = Auth::user();
    //     if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tài khoản của bạn bị chặn đến ' . $user->blocked_until->format('d/m/Y H:i') . '.',
    //         ], 403);
    //     }

    //     Chapter::where('id', $chapterId)->where('is_posted', 1)->firstOrFail();

    //     $comment = ChapterComment::create([
    //         'chapter_id'     => $chapterId,
    //         'user_id'        => $user->id,
    //         'content'        => $request->content,
    //         'parent_comment' => $request->parent_comment,
    //     ]);

    //     $comment->load('user');

    //     return response()->json([
    //         'success' => true,
    //         'comment' => [
    //             'id'         => $comment->id,
    //             'content'    => e($comment->content),
    //             'username'   => $comment->user->username,
    //             'created_at' => $comment->created_at->format('d/m/Y H:i'),
    //         ],
    //     ]);
    // }

    // // =========================================================
    // // [AJAX] Upvote bình luận chương (cần đăng nhập)
    // // Route: POST /chapter-comments/{commentId}/vote
    // // Lưu ý: DB dùng unsignedTinyInteger nên chỉ hỗ trợ upvote (count = 1)
    // //        Nếu muốn downvote thật sự → báo Tuấn Đạt đổi migration thành tinyInteger
    // // =========================================================
    // public function voteComment(Request $request, $commentId)
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
    //     }

    //     // FIX: kiểm tra user bị block không cho vote
    //     $user = Auth::user();
    //     if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tài khoản của bạn bị chặn đến ' . $user->blocked_until->format('d/m/Y H:i') . '.',
    //         ], 403);
    //     }

    //     ChapterComment::findOrFail($commentId);

    //     $existing = DB::table('upvote_chapter_comment_history')
    //         ->where('comment_id', $commentId)
    //         ->where('user_id', Auth::id())
    //         ->first();

    //     if ($existing) {
    //         // Bấm lại → hủy upvote
    //         DB::table('upvote_chapter_comment_history')
    //             ->where('comment_id', $commentId)
    //             ->where('user_id', Auth::id())
    //             ->delete();
    //         $voted = false;
    //     } else {
    //         DB::table('upvote_chapter_comment_history')->insert([
    //             'comment_id' => $commentId,
    //             'user_id'    => Auth::id(),
    //             'count'      => 1,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //         $voted = true;
    //     }

    //     $totalVote = DB::table('upvote_chapter_comment_history')
    //         ->where('comment_id', $commentId)
    //         ->sum('count');

    //     return response()->json([
    //         'success'    => true,
    //         'voted'      => $voted,
    //         'total_vote' => $totalVote,
    //     ]);
    // }

    // // =========================================================
    // // [AJAX] Xóa bình luận (chủ comment hoặc moderator)
    // // Route: DELETE /chapter-comments/{commentId}
    // // =========================================================
    // public function destroyComment($commentId)
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
    //     }

    //     $comment = ChapterComment::findOrFail($commentId);

    //     // FIX: cho phép moderator (guard khác) xóa comment của user
    //     $isModerator = Auth::guard('moderator')->check();

    //     if (!$isModerator && Auth::id() !== $comment->user_id) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Bạn không có quyền xóa bình luận này.',
    //         ], 403);
    //     }

    //     $comment->delete();

    //     return response()->json(['success' => true, 'message' => 'Đã xóa bình luận.']);
    // }
}