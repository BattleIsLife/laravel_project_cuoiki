<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterComment;
use App\Models\ModeratorPost;
use App\Models\ModeratorPostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function storeChapterComment(Request $request, string $chapterId)
    {
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        Chapter::where('id', $chapterId)
            ->where('is_posted', 1)
            ->firstOrFail();

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'parent_comment' => [
                'nullable',
                Rule::exists('chapter_comments', 'id')->where('chapter_id', $chapterId),
            ],
        ], $this->validationMessages());

        $comment = ChapterComment::create([
            'chapter_id' => $chapterId,
            'user_id' => $user->id,
            'content' => $validated['content'],
            'parent_comment' => $validated['parent_comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi bình luận.',
            'comment' => $this->formatComment($comment, $user),
        ], 201);
    }

    public function deleteChapterComment(Request $request, string $commentId)
    {
        $comment = ChapterComment::where('id', $commentId)->firstOrFail();

        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa bình luận này.',
            ], 403);
        }

        DB::transaction(function () use ($comment): void {
            $this->deleteCommentTree(ChapterComment::class, $comment->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa bình luận.',
            'deleted_id' => $commentId,
        ]);
    }

    public function storeModeratorPostComment(Request $request, string $postId)
    {
        $user = $request->user();

        if ($blockedResponse = $this->blockedResponse($user)) {
            return $blockedResponse;
        }

        ModeratorPost::where('id', $postId)->firstOrFail();

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'parent_comment' => [
                'nullable',
                Rule::exists('moderator_post_comments', 'id')->where('post_id', $postId),
            ],
        ], $this->validationMessages());

        $comment = ModeratorPostComment::create([
            'post_id' => $postId,
            'user_id' => $user->id,
            'content' => $validated['content'],
            'parent_comment' => $validated['parent_comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi bình luận.',
            'comment' => $this->formatComment($comment, $user),
        ], 201);
    }

    public function deleteModeratorPostComment(Request $request, string $commentId)
    {
        $comment = ModeratorPostComment::where('id', $commentId)->firstOrFail();

        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa bình luận này.',
            ], 403);
        }

        DB::transaction(function () use ($comment): void {
            $this->deleteCommentTree(ModeratorPostComment::class, $comment->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa bình luận.',
            'deleted_id' => $commentId,
        ]);
    }

    private function deleteCommentTree(string $modelClass, string $commentId): void
    {
        $modelClass::where('parent_comment', $commentId)
            ->pluck('id')
            ->each(function (string $childId) use ($modelClass): void {
                $this->deleteCommentTree($modelClass, $childId);
            });

        $modelClass::where('id', $commentId)->delete();
    }

    private function formatComment($comment, $user): array
    {
        return [
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'username' => $user->username,
            'content' => $comment->content,
            'parent_comment' => $comment->parent_comment,
            'created_at' => optional($comment->created_at)->toDateTimeString(),
            'updated_at' => optional($comment->updated_at)->toDateTimeString(),
        ];
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

    private function validationMessages(): array
    {
        return [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.max' => 'Bình luận không được vượt quá 5000 ký tự.',
            'parent_comment.exists' => 'Bình luận cha không hợp lệ.',
        ];
    }
}
