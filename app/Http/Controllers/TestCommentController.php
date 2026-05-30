<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class TestCommentController extends Controller
{
    // Thêm bình luận chương
    public function new_comment(Request $request, string $chapter_id)
    {
        $chapter = Chapter::whereId($chapter_id)->firstOrFail();

        $user = Auth::guard('web')->user();

        $comment = new ChapterComment();
        $comment->user_id = $user->id;
        $comment->chapter_id = $chapter_id;
        $comment->content = trim($request->comment_content);

        if($comment->save())
        {
            return response()->json([
                'id' => $comment->id,
                'username' => $user->username,
                'content' => $comment->content,
                'created_at' => $comment->created_at->toDateTimeString()
            ]);
        }

        return response()->json([
            'message' => 'Thất bại'
        ]);
    }

    // Thêm bình luận con của bình luận chương
    public function new_child_comment(Request $request, string $parentComment)
    {
        $parent_comment = ChapterComment::whereId($parentComment)->whereParentComment(null)->firstOrFail();

        $user = Auth::guard('web')->user();

        $child_comment = new ChapterComment();
        $child_comment->user_id = $user->id;
        $child_comment->chapter_id = $parent_comment->chapter_id;
        $child_comment->parent_comment = $parentComment;
        $child_comment->content = trim($request->content);

        if ($child_comment->save()) {
            return response()->json([
                'id' => $child_comment->id,
                'username' => $user->username,
                'content' => $child_comment->content,
                'created_at' => $child_comment->created_at->toDateTimeString()
            ]);
        }

        return response()->json([
            'message' => 'Thất bại'
        ]);
    }
}
