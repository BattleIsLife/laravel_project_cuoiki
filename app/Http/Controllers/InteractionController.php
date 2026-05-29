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
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        Fiction::where('id', $fictionId)->firstOrFail();

        $liked = $this->toggleLike('like_fiction_history', 'fiction_id', $fictionId, $user->id);
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

        Chapter::where('id', $chapterId)
            ->where('is_posted', 1)
            ->firstOrFail();

        $liked = $this->toggleLike('like_chapter_history', 'chapter_id', $chapterId, $user->id);
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
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        ChapterComment::where('id', $commentId)->firstOrFail();

        $vote = $this->normalizeVote($request);
        if ($vote === null) {
            return $this->invalidVoteResponse();
        }

        $currentVote = $this->toggleVote(
            'upvote_chapter_comment_history',
            'comment_id',
            $commentId,
            $user->id,
            $vote
        );

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
        $existing = DB::table($table)
            ->where($targetColumn, $targetId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->delete();

            return false;
        }

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
        $existing = DB::table($table)
            ->where($targetColumn, $targetId)
            ->where('user_id', $userId)
            ->first();

        if ($existing && (int) $existing->count === $vote) {
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->delete();

            return 0;
        }

        if ($existing) {
            DB::table($table)
                ->where($targetColumn, $targetId)
                ->where('user_id', $userId)
                ->update([
                    'count' => $vote,
                    'updated_at' => now(),
                ]);

            return $vote;
        }

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
