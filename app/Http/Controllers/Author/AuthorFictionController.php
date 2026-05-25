<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Fiction;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthorFictionController extends Controller
{
    public function index()
    {
        $fictions = Fiction::where('user_id', Auth::id())
            ->with('series')
            ->withCount('like_fiction_history')
            ->latest()
            ->paginate(10);

        return view('user.profile.list_fictions', compact('fictions'));
    }

    public function create()
    {
        $series = Series::where('user_id', Auth::id())
            ->orderBy('series_name')
            ->get();

        return view('fiction.new_fictions', compact('series'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateFiction($request);
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image_link'] = $request->file('image')->store('fiction_covers', 'public');
        }

        Fiction::create($validated);

        return redirect()->route('user.fiction_list')->with('success', 'Đã thêm truyện mới.');
    }

    public function edit($fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $series = Series::where('user_id', Auth::id())
            ->orderBy('series_name')
            ->get();

        $chapters = Chapter::where('fiction_id', $fiction->id)
            ->orderBy('chapter_order')
            ->get();

        return view('fiction.edit_fictions', compact('fiction', 'series', 'chapters'));
    }

    public function update(Request $request, $fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $this->validateFiction($request);

        if ($request->hasFile('image')) {
            if ($fiction->image_link) {
                Storage::disk('public')->delete($fiction->image_link);
            }

            $validated['image_link'] = $request->file('image')->store('fiction_covers', 'public');
        }

        $fiction->update($validated);

        return redirect()->route('user.edit_fiction', $fiction->id)->with('success', 'Đã cập nhật thông tin truyện.');
    }

    public function delete($fictionId)
    {
        $fiction = Fiction::where('id', $fictionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($fiction->image_link) {
            Storage::disk('public')->delete($fiction->image_link);
        }

        $fiction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa truyện.',
        ]);
    }

    private function validateFiction(Request $request): array
    {
        return $request->validate([
            'fiction_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'series_id' => [
                'nullable',
                Rule::exists('series', 'id')->where('user_id', Auth::id()),
            ],
            'image' => ['nullable', 'image', 'max:2048'],
        ], [
            'fiction_name.required' => 'Vui lòng nhập tên truyện.',
            'fiction_name.max' => 'Tên truyện không được vượt quá 100 ký tự.',
            'series_id.exists' => 'Series không hợp lệ.',
            'image.image' => 'File bìa truyện phải là ảnh.',
            'image.max' => 'Ảnh bìa không được vượt quá 2MB.',
        ]);
    }
}
