<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Fiction;
use App\Models\Series;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorSeriesController extends Controller
{
    // List series của nguiwof dùng
    public function index()
    {
        $allSeries = Series::whereUserId(Auth::guard('web')->user()->id)
            ->latest()
            ->paginate(10);

        $data = [
            'allSeries' => $allSeries
        ];

        return view('user.profile.list_series', $data);
    }


    // Tạo series mới
    public function create()
    {
        return view('series.new_series');
    }

    public function create_attempt(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'series_name'    => 'required|string|max:100',
            'image'      => 'image|mimes:png,jpg,jpeg,webp|max:3072'
        ], 
        [
            'series_name.required' => 'Vui lòng nhập tên truyện',
        ]);

        // Kiểm tra điều kiện tồn tại series chưa
        $exist_series = Series::whereSeriesName(trim($request->series_name))->whereUserId(Auth::guard('web')->user()->id)->first();

        // Trả về nếu tồn tại
        if($exist_series)
        {
            return back()->with('error', 'Bạn đã có series này rồi')->withInput();
        }

        // Nếu không, tiếp tục xử lý tiếp
        // Xử lý upload ảnh (chỉ xử lý nếu như người dùng có upload)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Đặt tên file độc nhất bằng thời gian để tránh trùng lặp đè file cũ
            $fileName = time() . '_' . Str::random(10) . '.' . $request->image->extension();
            
            // Lưu file vào thư mục 'storage/app/public/upload/series'
            $imagePath = $file->storeAs('upload/series', $fileName, 'public');
        }
        // Nếu khong upload ảnh, fallback về default
        else
            $imagePath = "default.jpeg";

        // Lưu trữ giữ liệu
        $data = [
            'series_name' => trim($request->series_name),
            'image_link' => $imagePath,
            'user_id' => Auth::guard('web')->user()->id,
            'description' => trim($request->description)
        ];

        if (Series::create($data)) {
            return redirect()->route('user.series_list')->with('success', 'Thêm series mới thành công!!');
        }

        return back()->with('error', 'Thêm series thất bại')->withInput();
    }


    // Sửa series
    public function edit(string $seriesId)
    {
        $series = Series::whereId($seriesId)
                        ->where('user_id', Auth::guard('web')->user()->id)
                        ->firstOrFail();

        $fictions_in_series = $series->fictions()->paginate(10);
        $fiction_not_in_series = Fiction::whereUserId(Auth::guard('web')->user()->id)
                                          ->whereSeriesId(null)->get();

        $data = [
            'series' => $series,
            'fictions_in_series' => $fictions_in_series,
            'fictions_not_in_series' => $fiction_not_in_series
        ];

        return view('series.edit_series', $data);
    }

    public function edit_attempt(Request $request, string $seriesId)
    {
        // Validate dữ liệu
        $request->validate([
            'series_name'    => 'required|string|max:100',
            'image'      => 'image|mimes:png,jpg,jpeg,webp|max:3072'
        ], 
        [
            'series_name.required' => 'Vui lòng nhập tên series',
        ]);

        // Kiểm tra điều kiện tồn tại series chưa
        $exist_series = Series::whereSeriesName(trim($request->series_name))
                            ->whereUserId(Auth::guard('web')->user()->id)
                            ->first();

        // Trả về nếu tồn tại
        if($exist_series && $exist_series->id !== $seriesId)
        {
            return back()->with('error', 'Trùng tên series, vui lòng thử lại')->withInput();
        }

        $current_series = Series::whereUserId(Auth::guard('web')->user()->id)->whereId($seriesId)->first();

        // Nếu không, tiếp tục xử lý tiếp
        // Xử lý upload ảnh (chỉ xử lý nếu như người dùng có upload)
        $imagePath = $current_series->image_link;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Không xóa ảnh default
            if($imagePath !== 'default.jpeg')
                Storage::disk('public')->delete($imagePath);

            // Đặt tên file độc nhất bằng thời gian để tránh trùng lặp đè file cũ
            $fileName = time() . '_' . Str::random(10) . '.' . $request->image->extension();
            
            // Lưu file vào thư mục 'storage/app/public/upload/series'
            $imagePath = $file->storeAs('upload/series', $fileName, 'public');
        }
        // Nếu khong upload ảnh, không đổi gì cả

        // Update dữ liệu
        $current_series->series_name = trim($request->series_name);
        $current_series->image_link = $imagePath;
        $current_series->description = trim($request->description);
        if($current_series->save())
            return redirect()->route('user.series_list')->with('success', 'Sửa series thành công');

        return back()->with('error', 'Sửa series thất bại')->withInput();
    }


    // Thêm truyện vào series
    public function add_fiction_to_series(Request $request, string $seriesId)
    {
        $request->validate([
            'fictions' => 'required|exists:fictions,id'
        ], 
        [
            'fictions.required' => 'Vui lòng chọn 1 truyện',
        ]);

        $series = Series::whereId($seriesId)
                        ->where('user_id', Auth::guard('web')->user()->id)
                        ->firstOrFail();
        
        if(!$series)
            return redirect()->route('user.series_list')->with('error', 'Series không tồn tại');


        $fiction = Fiction::whereUserId(Auth::guard('web')->user()->id)->whereId($request->fictions)->firstOrFail();
        if(!$fiction)
            return back()->with('error', 'Truyện không tồn tại');
        else if($fiction->series_id != null)
            return back()->with('error', 'Truyện đã có series');

        $fiction->series_id = $seriesId;
        if($fiction->save())
            return back()->with('success', 'Thêm truyện vào series thành công');

    }

    // Xóa truyện khỏi series
    public function delete_fiction_from_series(string $fictionId)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để truy cập.',
            ], 401);
        }

        $fiction = Fiction::whereId($fictionId)
                            ->whereUserId(Auth::guard('web')->user()->id)
                            ->firstOrFail();

        $fiction->series_id = null;

        if($fiction->saveOrFail())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa truyện khỏi series.',
            ]);
        
        return response()->json([
                'success' => false,
                'message' => 'Đã xóa thất bại.',
            ]);
    }

    public function delete(string $seriesId)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để truy cập.',
            ], 401);
        }

        $series = Series::whereId($seriesId)
            ->whereUserId(Auth::guard('web')->user()->id)
            ->firstOrFail();

        if($series->deleteOrFail())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa series.',
            ]);
        
        return response()->json([
                'success' => false,
                'message' => 'Đã xóa thất bại.',
            ]);
    }
}
