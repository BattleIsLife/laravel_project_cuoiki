<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Fiction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorChapterController extends Controller
{
    // =========================================================
    // Danh sách chương của một truyện
    // Route: GET /author/fictions/{fictionId}/chapters
    // View : chapter/all_chapters
    // =========================================================
    public function index($fictionId)
    {
        // Chỉ lấy truyện của tác giả đang đăng nhập
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $chapters = Chapter::where('fiction_id', $fictionId)
            ->orderBy('chapter_order')
            ->paginate(20);

        return view('chapter.all_chapters', compact('fiction', 'chapters'));
    }

    // =========================================================
    // Form tạo chương mới
    // Route: GET /author/fictions/{fictionId}/chapters/create
    // View : chapter/new_chapters
    // =========================================================
    public function create($fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Số thứ tự gợi ý = max hiện tại + 1
        $nextOrder = (Chapter::where('fiction_id', $fictionId)->max('chapter_order') ?? 0) + 1;

        return view('chapter.new_chapters', compact('fiction', 'nextOrder'));
    }

    // =========================================================
    // Lưu chương mới (draft hoặc đăng luôn)
    // Route: POST /author/fictions/{fictionId}/chapters
    // =========================================================
    public function store(Request $request, $fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'chapter_name'  => 'required|string|max:100|unique:chapters,chapter_name',
            'chapter_order' => 'required|integer|min:1',
            'content'       => 'nullable|string',
            'action'        => 'required|in:draft,publish',
        ], [
            'chapter_name.required' => 'Vui lòng nhập tên chương.',
            'chapter_name.unique'   => 'Tên chương này đã tồn tại.',
            'chapter_name.max'      => 'Tên chương không được quá 100 ký tự.',
            'chapter_order.required'=> 'Vui lòng nhập số thứ tự chương.',
            'chapter_order.min'     => 'Số thứ tự phải lớn hơn 0.',
        ]);

        Chapter::create([
            'fiction_id'    => $fictionId,
            'chapter_name'  => $request->chapter_name,
            'chapter_order' => $request->chapter_order,
            'content'       => $request->content,
            'is_posted'     => $request->action === 'publish' ? 1 : 0,
            'watch_count'   => 0,
        ]);

        $msg = $request->action === 'publish'
            ? 'Đã đăng chương thành công!'
            : 'Đã lưu bản nháp.';

        return redirect()
            ->route('author.chapters.index', $fictionId)
            ->with('success', $msg);
    }

    // =========================================================
    // Form chỉnh sửa chương
    // Route: GET /author/fictions/{fictionId}/chapters/{chapterId}/edit
    // View : chapter/edit_chapters
    // =========================================================
    public function edit($fictionId, $chapterId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $chapter = Chapter::where('id', $chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        return view('chapter.edit_chapters', compact('fiction', 'chapter'));
    }

    // =========================================================
    // Cập nhật nội dung chương (draft hoặc đăng)
    // Route: PUT /author/fictions/{fictionId}/chapters/{chapterId}
    // =========================================================
    public function update(Request $request, $fictionId, $chapterId)
    {
        Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $chapter = Chapter::where('id', $chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        $request->validate([
            'chapter_name'  => 'required|string|max:100|unique:chapters,chapter_name,' . $chapterId,
            'chapter_order' => 'required|integer|min:1',
            'content'       => 'nullable|string',
            'action'        => 'required|in:draft,publish',
        ], [
            'chapter_name.required' => 'Vui lòng nhập tên chương.',
            'chapter_name.unique'   => 'Tên chương này đã tồn tại.',
            'chapter_name.max'      => 'Tên chương không được quá 100 ký tự.',
            'chapter_order.required'=> 'Vui lòng nhập số thứ tự chương.',
            'chapter_order.min'     => 'Số thứ tự phải lớn hơn 0.',
        ]);

        $chapter->update([
            'chapter_name'  => $request->chapter_name,
            'chapter_order' => $request->chapter_order,
            'content'       => $request->content,
            'is_posted'     => $request->action === 'publish' ? 1 : 0,
        ]);

        $msg = $request->action === 'publish'
            ? 'Đã cập nhật và đăng chương!'
            : 'Đã lưu bản nháp.';

        return redirect()
            ->route('author.chapters.edit', [$fictionId, $chapterId])
            ->with('success', $msg);
    }

    // =========================================================
    // [AJAX] Xóa chương
    // Route: DELETE /author/fictions/{fictionId}/chapters/{chapterId}
    // =========================================================
    public function destroy($fictionId, $chapterId)
    {
        Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $chapter = Chapter::where('id', $chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        $chapter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa chương.',
        ]);
    }

    // =========================================================
    // [AJAX] Đổi trạng thái draft ↔ published nhanh từ danh sách
    // Route: POST /author/fictions/{fictionId}/chapters/{chapterId}/toggle
    // =========================================================
    public function togglePublish($fictionId, $chapterId)
    {
        Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $chapter = Chapter::where('id', $chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        $chapter->update(['is_posted' => $chapter->is_posted ? 0 : 1]);

        return response()->json([
            'success'   => true,
            'is_posted' => $chapter->is_posted,
            'label'     => $chapter->is_posted ? 'Đã đăng' : 'Bản nháp',
        ]);
    }
}