<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Moderator;
use App\Models\ModeratorPost;
use App\Models\ModeratorPostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    // =========================================================
    // Kiểm tra quyền post_moderator or admin
    // Dùng nội bộ để tránh lặp code
    // =========================================================
    private function checkPermission(): bool
    {
        $moderator = Auth::guard('moderator')->user();
        return $moderator->permission  === 'post_moderator' || $moderator->permission === "admin";
    }


    // =========================================================
    // Danh sách bài đăng
    // Route: GET /admin/post_list
    // View : admin.post.all_posts
    // =========================================================
    public function showAllPost(Request $request)
    {
        // Chỉ admin và post moderator có thể xem danh sách bài đăng
        // Chỉ post_moderator mới có thể thêm/sửa/xóa
        $moderator = Auth::guard('moderator')->user();
        if (!$this->checkPermission()) {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $keyword = trim((string) $request->query('q', ''));

        $posts = ModeratorPost::with('moderator')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhereHas('moderator', function ($modQuery) use ($keyword) {
                        $modQuery->where('username', 'like', "%{$keyword}%");
                    });
            })
            ->orderByRaw("moderator_id = ? DESC", [$moderator->id])->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.profile.all_posts', compact('posts', 'keyword', 'moderator'));
    }


    // =========================================================
    // Xem chi tiết bài đăng (public, kể cả user thường xem)
    // Route: GET /admin/post/{post_id}   (hoặc tùy route bạn đặt)
    // View : admin.post.detail_post
    // =========================================================
    public function show(string $post_id)
    {
        $moderator = auth()->guard('moderator')->user();
        $user = auth()->guard('web')->user();
        $post = ModeratorPost::with(['moderator', 'comments.user'])
            ->findOrFail($post_id);

        // Lấy comment gốc (không có parent), kèm comment con
        $comments = ModeratorPostComment::with(['user', 'child_comment.user'])
            ->withSum('upvote_history as total_score', 'count')
            ->where('post_id', $post_id)
            ->whereNull('parent_comment')
            ->latest()
            ->paginate(15);

        return view('post.detail_post', compact('post', 'comments', 'user', 'moderator'));
    }


    // =========================================================
    // Form thêm bài đăng mới
    // Route: GET /admin/new_post
    // View : admin.post.new_post
    // =========================================================
    public function showNewPost()
    {
        if (!$this->checkPermission()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        return view('post.new_post');
    }


    // =========================================================
    // Xử lý thêm bài đăng mới
    // Route: POST /admin/new_post
    // =========================================================
    public function new_post(Request $request)
    {
        if (!$this->checkPermission()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $request->validate([
            'title'       => 'required|string|max:100|unique:moderator_posts,title',
            'description' => 'required|string',
        ], [
            'title.required'     => 'Vui lòng nhập tiêu đề bài đăng.',
            'title.max'          => 'Tiêu đề không được vượt quá 100 ký tự.',
            'title.unique'       => 'Tiêu đề này đã tồn tại, vui lòng chọn tiêu đề khác.',
            'description.required' => 'Vui lòng nhập nội dung bài đăng.',
        ]);

        ModeratorPost::create([
            'title'        => trim($request->title),
            'description'  => $request->description,
            'moderator_id' => Auth::guard('moderator')->user()->id,
        ]);

        return redirect()->route('admin.post_list')
            ->with('success', 'Đăng bài thành công.');
    }


    // =========================================================
    // Form sửa bài đăng
    // Route: GET /admin/edit_post/{post_id}
    // View : admin.post.edit_post
    // =========================================================
    public function showEditPost(string $post_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== 'post_moderator') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $post = ModeratorPost::whereModeratorId($moderator->id)->findOrFail($post_id);

        return view('post.edit_post', compact('post'));
    }


    // =========================================================
    // Xử lý sửa bài đăng
    // Route: POST /admin/edit_post/{post_id}
    // =========================================================
    public function edit_post(Request $request, string $post_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== 'post_moderator') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $post = ModeratorPost::whereModeratorId(Auth::guard('moderator')->user()->id)->findOrFail($post_id);

        $request->validate([
            'title'       => 'required|string|max:100|unique:moderator_posts,title,' . $post_id,
            'description' => 'required|string',
        ], [
            'title.required'       => 'Vui lòng nhập tiêu đề bài đăng.',
            'title.max'            => 'Tiêu đề không được vượt quá 100 ký tự.',
            'title.unique'         => 'Tiêu đề này đã tồn tại, vui lòng chọn tiêu đề khác.',
            'description.required' => 'Vui lòng nhập nội dung bài đăng.',
        ]);

        $post->title       = trim($request->title);
        $post->description = $request->description;

        if ($post->save()) {
            return redirect()->route('admin.post_list')
                ->with('success', 'Cập nhật bài đăng thành công.');
        }

        return back()->with('error', 'Cập nhật bài đăng thất bại.')->withInput();
    }


    // =========================================================
    // [AJAX] Xóa bài đăng (kèm toàn bộ comment)
    // Route: DELETE /admin/delete_post/{post_id}
    // =========================================================
    public function delete_post(string $post_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== 'post_moderator') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        $post = ModeratorPost::whereModeratorId(Auth::guard('moderator')->user()->id)->findOrFail($post_id);

        if($post->delete())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa bài đăng.',
            ]);
    }


    // =========================================================
    // [AJAX] Xóa comment bài đăng (dành cho post_moderator)
    // Route: DELETE /admin/delete_post_comment/{comment_id}
    // =========================================================
    public function delete_post_comment(string $comment_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== 'post_moderator') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        $comment = ModeratorPostComment::where('id', $comment_id)->firstOrFail();

        DB::transaction(function () use ($comment): void {
            $this->deleteCommentTree(ModeratorPostComment::class, $comment->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa bình luận.',
        ]);
    }


    // =========================================================
    // Hàm đệ quy xóa comment con (giống ManagmentController)
    // =========================================================
    private function deleteCommentTree(string $modelClass, string $commentId): void
    {
        $modelClass::where('parent_comment', $commentId)
            ->pluck('id')
            ->each(function (string $childId) use ($modelClass): void {
                $this->deleteCommentTree($modelClass, $childId);
            });

        $modelClass::where('id', $commentId)->delete();
    }
}