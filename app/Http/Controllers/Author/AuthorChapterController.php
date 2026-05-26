<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fiction;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class AuthorChapterController extends Controller
{
    // =========================================================
    // Form tạo chương mới
    // Route: GET /author/edit_fictions/{fictionId}/chapters/create
    // View : chapter/new_chapters
    // =========================================================
    public function create(string $fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail();

        // Số thứ tự gợi ý = max hiện tại + 1
        $nextOrder = (Chapter::where('fiction_id', $fictionId)->max('chapter_order') ?? 0) + 1;

        return view('chapter.new_chapters', compact('fiction', 'nextOrder'));
    }

    // =========================================================
    // Lưu chương mới (draft hoặc đăng luôn)
    // Route: POST /author/fictions/{fictionId}/chapters
    // =========================================================
    public function store(Request $request, string $fictionId)
    {
        $fiction = Fiction::whereId($fictionId)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail('id');

        $request->validate([
            'chapter_name' => [
                'required', 'string', 'max:100',
                // FIX: unique chỉ trong cùng 1 truyện, không phải toàn bảng
                Rule::unique('chapters', 'chapter_name')->where('fiction_id', $fictionId),
            ],
            'chapter_order' => 'required|integer|min:1',
            'content'       => 'nullable|string',
        ], [
            'chapter_name.required' => 'Vui lòng nhập tên chương.',
            'chapter_name.unique'   => 'Tên chương này đã tồn tại trong truyện.',
            'chapter_name.max'      => 'Tên chương không được quá 100 ký tự.',
            'chapter_order.required'=> 'Vui lòng nhập số thứ tự chương.',
            'chapter_order.min'     => 'Số thứ tự phải lớn hơn 0.',
        ]);

        Chapter::create([
            'fiction_id'    => $fictionId,
            'chapter_name'  => $request->chapter_name,
            'chapter_order' => $request->chapter_order,
            'content'       => Purifier::clean($request->content),
            'is_posted'     => !$request->boolean('save_as_draft') ? 1 : 0,
            'watch_count'   => 0,
        ]);

        $msg = !$request->boolean('save_as_draft')
            ? 'Đã đăng chương thành công!'
            : 'Đã lưu bản nháp.';

        return redirect()
            ->route('user.edit_fiction', $fictionId)
            ->with('success', $msg);
    }

    // =========================================================
    // Form chỉnh sửa chương
    // Route: GET /author/edit_fiction/{fictionId}/edit_chapter/{chapterId}
    // View : chapter/edit_chapters
    // =========================================================
    public function edit(string $fictionId, string $chapterId)
    {
        $fiction = Fiction::whereId($fictionId)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail('id');

        $chapter = Chapter::whereId($chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        return view('chapter.edit_chapters', compact('fiction', 'chapter'));
    }

    // =========================================================
    // Cập nhật nội dung chương (draft hoặc đăng)
    // Route: PUT /author/edit_fiction/{fictionId}/edit_chapter/{chapterId}
    // =========================================================
    public function update(Request $request, string $fictionId, string $chapterId)
    {
        Fiction::whereId($fictionId)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail('id');

        $chapter = Chapter::whereId($chapterId)
            ->where('fiction_id', $fictionId)
            ->firstOrFail();

        $request->validate([
            'chapter_name' => [
                'required', 'string', 'max:100',
                // FIX: unique chỉ trong cùng 1 truyện, không phải toàn bảng
                Rule::unique('chapters', 'chapter_name')->where('fiction_id', $fictionId)->whereNot('id', $chapterId),
            ],
            'chapter_order' => 'required|integer|min:1',
            'content'       => 'nullable|string',
        ], [
            'chapter_name.required' => 'Vui lòng nhập tên chương.',
            'chapter_name.unique'   => 'Tên chương này đã tồn tại trong truyện.',
            'chapter_name.max'      => 'Tên chương không được quá 100 ký tự.',
            'chapter_order.required'=> 'Vui lòng nhập số thứ tự chương.',
            'chapter_order.min'     => 'Số thứ tự phải lớn hơn 0.',
        ]);

        $chapter->chapter_name = $request->chapter_name;
        $chapter->chapter_order = $request->chapter_order;
        $chapter->content = Purifier::clean($request->content);
        $chapter->is_posted = !$request->boolean('save_as_draft') ? 1 : 0;

        $chapter->save();

        $msg = !$request->boolean('save_as_draft')
            ? 'Đã cập nhật và đăng chương!'
            : 'Đã lưu bản nháp.';

        return redirect('author/edit_fiction/'. $fictionId)->with('success', $msg);
    }

    // =========================================================
    // [AJAX] Xóa chương
    // Route: DELETE /author/fictions/{fictionId}/chapters/{chapterId}
    // =========================================================
    public function destroy(string $fictionId, string $chapterId)
    {
        Fiction::where('id', $fictionId)
            ->where('user_id', Auth::guard('web')->user()->id)
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
}
