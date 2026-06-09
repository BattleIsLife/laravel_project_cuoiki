<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterComment;
use App\Models\Fiction;
use App\Models\ModeratorPostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function toggleFictionLike(Request $request, string $fictionId)
    {
        $user = $request->user(); //lấy người dùng hiện tại

        //Kiểm tra tài khoản có bị khóa không. Nếu bị khóa thì dừng lại.
        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        //Kiểm tra truyện có tồn tại không. Không có thì báo lỗi 404.
        Fiction::where('id', $fictionId)->firstOrFail();

        //Nếu chưa Like thì thêm Like, nếu đã Like thì bỏ Like.
        $liked = $this->toggleLike('like_fiction_history', 'fiction_id', $fictionId, $user->id);

        //Đếm tổng số lượt Like của truyện.
        $likeCount = DB::table('like_fiction_history')
            ->where('fiction_id', $fictionId)
            ->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
        ]);
    }

    public function toggleChapterLike(Request $request, string $chapterId)
    {
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        //Kiểm tra chương có tồn tại không. Không có thì báo lỗi 404.
        Chapter::where('id', $chapterId)
            ->where('is_posted', 1)
            ->firstOrFail();

        //Nếu chưa Like thì thêm Like, nếu đã Like thì bỏ Like.
        $liked = $this->toggleLike('like_chapter_history', 'chapter_id', $chapterId, $user->id);
        //Đếm tổng số lượt Like của chương.
        $likeCount = DB::table('like_chapter_history')
            ->where('chapter_id', $chapterId)
            ->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
        ]);
    }

    public function voteChapterComment(Request $request, string $commentId)
    {   
        // Lấy thông tin người dùng đang đăng nhập
        $user = $request->user();
        // Kiểm tra tài khoản có đang bị chặn không
        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }
        // Kiểm tra bình luận chương có tồn tại không
        ChapterComment::where('id', $commentId)->firstOrFail();
        // Chuẩn hóa giá trị vote từ request
        $vote = $this->normalizeVote($request);
        if ($vote === null) {
            return $this->invalidVoteResponse();
        }
        // Thực hiện toggle vote
        $currentVote = $this->toggleVote(
            'upvote_chapter_comment_history',
            'comment_id',
            $commentId,
            $user->id,
            $vote
        );
        // Tính tổng điểm vote của bình luận
        $totalVote = DB::table('upvote_chapter_comment_history')
            ->where('comment_id', $commentId)
            ->sum('count');

        return response()->json([
            'success' => true,
            'vote' => $currentVote,
            'total_vote' => $totalVote,
        ]);
    }

    public function voteModeratorPostComment(Request $request, string $commentId)
    {
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        ModeratorPostComment::where('id', $commentId)->firstOrFail();

        $vote = $this->normalizeVote($request);
        if ($vote === null) {
            return $this->invalidVoteResponse();
        }

        $currentVote = $this->toggleVote(
            'upvote_moderator_post_comment_history',
            'comment_id',
            $commentId,
            $user->id,
            $vote
        );

        $totalVote = DB::table('upvote_moderator_post_comment_history')
            ->where('comment_id', $commentId)
            ->sum('count');

        return response()->json([
            'success' => true,
            'vote' => $currentVote,
            'total_vote' => $totalVote,
        ]);
    }

    private function blockedResponse($user)
    {   
        // Kiểm tra người dùng có thời gian bị chặn hay không
        // Nếu thời gian hiện tại vẫn nhỏ hơn thời gian bị chặn
        // thì người dùng chưa được phép tương tác
        if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn bị chặn đến ' . $user->blocked_until->format('d/m/Y H:i') . '.',
            ], 403);
        }

        return null;
    }

    private function toggleLike(string $table, string $targetColumn, string $targetId, string $userId): bool
    {
        //Kiểm tra người dùng đã like chưa.
        $existing = DB::table($table)
            ->where($targetColumn, $targetId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->delete();

            return false; // nếu rồi trả về false
        }
        // nếu chưa like thì thêm bản ghi mới
        DB::table($table)->insert([
            $targetColumn => $targetId,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    private function toggleVote(
        string $table,
        string $targetColumn,
        string $targetId,
        string $userId,
        int $vote
    ): int {
        $existing = DB::table($table) //kiểm tra người dùng đã vote chưa
            ->where($targetColumn, $targetId)
            ->where('user_id', $userId)
            ->first();

        if ($existing && (int) $existing->count === $vote) { // Nếu đã vote cùng loại (Upvote→Upvote hoặc Downvote→Downvote)
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->delete();                                  // Xóa vote hiện tại (bỏ vote)

            return 0;                               // Trả về 0 để biểu thị đã bỏ vote      
        }

        if ($existing) {                        // Nếu đã vote nhưng khác loại (Upvote→Downvote hoặc Downvote→Upvote)   
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->update([
                    'count' => $vote,
                    'updated_at' => now(),          // Cập nhật loại vote mới
                ]);

            return $vote;             // Trả về loại vote mới
        }
        // Nếu người dùng chưa vote lần nào
        DB::table($table)->insert([
            $targetColumn => $targetId,
            'user_id' => $userId,
            'count' => $vote,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $vote;
    }

    private function normalizeVote(Request $request): ?int
    {
        $vote = $request->input('vote', $request->input('count', 1));

        if (in_array($vote, [1, '1', 'up', 'upvote'], true)) {
            return 1;
        }

        if (in_array($vote, [-1, '-1', 'down', 'downvote'], true)) {
            return -1;
        }

        return null;
    }

    private function invalidVoteResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Giá trị vote không hợp lệ.',
        ], 422);
    }
}
