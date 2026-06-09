<?php

namespace App\Http\Controllers; // Khai báo lớp thuộc thư mục Controllers

use App\Models\Series; // Sử dụng model Series để tương tác với bảng series trong cơ sở dữ liệu
use Illuminate\Http\Request; // Lấy dữ liêu từ yêu cầu HTTP (URL. form, request)
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller // khai báo series comtroller kế thừa từ Controller nên có thể
                                          // sử dụng tính năng chung của laravel controller
{
    public function index(Request $request) // nhận request từ URL để lấy dữ liệu tìm kiếm và phân trang
    {

        $keyword = trim((string) $request->query('q', '')); //lấy từ khóa tìm kiếm trên URL

        // Chỉ hiển thị các series mà có ít nhất 1 truyện đăng chương
        $allSeries = Series::with('author') // tải cả thông tin series và tác giả 
            ->whereHas('fictions', function ($fiction) {
                $fiction->whereHas('chapters', function ($chapter) {
                    $chapter->where('is_posted', '=', 1);
                });
            })
            // Tìm kiếm theo tên series hoặc tên tác giả
            ->when($keyword !== '', function($query) use ($keyword){
                $query->where('series_name', 'like', "%{$keyword}%")
                      ->orWhereHas('author', function ($authorQuery) use ($keyword) {
                        $authorQuery->where('username', 'like', "%{$keyword}%");
                    });
            })
            ->latest() // series mới nhất sẽ được hiển thị trước
            ->paginate(10)->withQueryString(); // mỗi trang hiển thị 10 series và giữ lại từ khóa khi chuyển trang

        return view('series.all_series', compact('allSeries', 'keyword')); // trả dữ liệu sang view
    }

    public function show(string $seriesId) // nhận tham số seriesId từ URL để hiển thị chi tiết series và các truyện thuộc series đó
    {
        $series = Series::whereHas('fictions') // series phải có ít nhất 1 truyện mới được hiển thị
            ->findOrFail($seriesId); // tìm series theo id, nếu không tìm thấy sẽ trả về lỗi 404

            // lấy các truyện thuộc series đó mà có ít nhất 1 chương đã đăng, đếm số lượt thích của mỗi truyện, sắp xếp theo thời gian đăng mới nhất và phân trang
        $fictions = $series->fictions()->whereHas('chapters', function ($chapter) {
                    $chapter->where('is_posted', '=', 1);
                })
                ->withCount('like_fiction_history')
                ->latest()
                ->paginate(10);

        return view('series.detail_series', compact('series', 'fictions')); // trả dữ liệu sang view chi tiết series
    }
}
