<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\ChapterComment;
use App\Models\Moderator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Fiction;
use App\Models\ModeratorPostComment;
use App\Models\Series;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ManagmentController extends Controller
{
    public function dashboard(Request $request)
    {
        // KIỂM TRA: Nếu bộ lọc KHÔNG PHẢI là custom nhưng URL lại chứa start_date hoặc end_date
        if ($request->query('filter') !== 'custom' && ($request->has('start_date') || $request->has('end_date'))) {
            
            // Tiến hành lọc bỏ hoàn toàn start_date và end_date ra khỏi danh sách tham số
            $cleanQueries = $request->except(['start_date', 'end_date']);
            
            // Chuyển hướng người dùng về chính trang này với URL đã được làm sạch rác
            return redirect()->to($request->url() . '?' . http_build_query($cleanQueries));
        }
        $moderator = Auth::guard('moderator')->user();
        $dashboard_data = [];
        // Dành cho các moderator khác, không áp dụng bộ lọc
        if ($moderator->permission === 'admin') {
            $dashboard_data = $this->admin_dashboard($request);
        }
        else
        {
            $dashboard_data = $this->normal_dashboard();
        }

        $data = [
            'dashboard_data' => $dashboard_data,
            'moderator'     => $moderator
        ];

        return view('admin.profile.dashboard', $data);
    }


    // Dashboard cho người không phải là admin
    private function normal_dashboard()
    {
        // 1. Lấy ngày hôm nay dưới dạng Y-m-d bằng thư viện Carbon có sẵn của Laravel
        $today = Carbon::today();

        // 2. Tính tổng lượt đọc của các chương được xem trong ngày hôm nay
        $totalReadsToday = Chapter::whereDate('updated_at', $today)->sum('watch_count');

        // 3. Đếm tổng số bình luận được viết trong ngày hôm nay
        $totalCommentsToday = ChapterComment::whereDate('created_at', $today)->count() + ModeratorPostComment::whereDate('created_at', $today)->count();

        // 4. Đếm số chương truyện thực sự được bấm ĐĂNG TẢI (is_posted = 1) trong ngày hôm nay
        $totalChaptersToday = Chapter::where('is_posted', 1)
                                    ->whereDate('created_at', $today)
                                    ->count();
        $totalNewUsersToday = User::whereDate('created_at', $today)->count();

        $dashboard_data = [
            'totalReadsToday' => $totalReadsToday,
            'totalCommentsToday' => $totalCommentsToday,
            'totalChaptersToday' => $totalChaptersToday,
            'totalNewUsersToday' => $totalNewUsersToday
        ];

        return $dashboard_data;
    }

    // Dashboard cho admin
    private function admin_dashboard(Request $request)
    {
        // 1. Xác định khoảng thời gian dựa trên bộ lọc (Mặc định nếu không chọn là hôm nay)
        $filter = $request->query('filter', 'today'); 
        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        // Xử lý các trường hợp bộ lọc mà Admin chọn ngoài giao diện
        switch ($filter) {
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
                
            case '7_days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
                
            case 'custom':
                // Nếu admin chọn khoảng ngày bất kỳ (Ví dụ từ hệ thống chọn ngày picker)
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
                }
                break;
                
            default: // 'today'
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
        }

        // 2. Tiến hành truy vấn sử dụng hàm whereBetween() thay vì whereDate() để quét theo khoảng thời gian dynamic
        $totalReads = Chapter::whereBetween('updated_at', [$startDate, $endDate])->sum('watch_count');

        $totalComments = ChapterComment::whereBetween('created_at', [$startDate, $endDate])->count() 
                    + ModeratorPostComment::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalChapters = Chapter::where('is_posted', 1)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count();

        $totalNewUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $message = "Báo cáo ";
        if($startDate->format('d-m-Y') === $endDate->format('d-m-Y'))
            $message .= "trong ngày " . $startDate->format('d-m-Y');
        else
            $message .= "từ ngày " . $startDate->format('d-m-Y') . " đến ngày ". $endDate->format('d-m-Y');

        // Trả về kết quả kèm theo thông tin filter hiện tại để ngoài View biết đường hiển thị trạng thái active
        return [
            'totalReadsToday'    => $totalReads,
            'totalCommentsToday' => $totalComments,
            'totalChaptersToday' => $totalChapters,
            'totalNewUsersToday' => $totalNewUsers,
            'current_filter'     => $filter,
            'message'            => $message,
            'start_date'         => $startDate->format('Y-m-d'),
            'end_date'           => $endDate->format('Y-m-d'),
        ];
    }


    // User moderator
    // Danh sách người dùng
    public function user_list(Request $request)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $keyword = trim((string) $request->query('q', ''));
        $users = User::withTrashed()->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%");
            })->latest('created_at')->paginate(10)->withQueryString();


        $data = [
            'users' => $users,
            'keyword' => $keyword,
            'moderator' => $moderator
        ];

        return view('admin.profile.all_users', $data);
    }

    // API: Lấy người dùng từ id
    // POST: /admin/get_user_info/{id}
    public function get_user_info(string $id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }
        $user = User::whereId($id)->firstOrFail();

        return response()->json([
            'user' => $user,
            'success' => true
        ]);
    }

    public function block_user(Request $request, string $user_id)
    {
        $request->validate([
            'blocked_until' => 'nullable'
        ]);

        if($request->blocked_until)
        {
            $myDate = Carbon::parse($request->blocked_until);
            if($myDate->isPast())
                return back()->with('error', 'Không thể chỉnh thời gian chặn về quá khứ');
        }

        $user = User::whereId($user_id)->firstOrFail();

        $user->blocked_until = $request->blocked_until;

        if($user->save())
        {
            if($request->blocked_until)
                DB::table('sessions')->where('user_id', $user_id)->delete();
            return back()->with('success', 'Đã thay đổi trạng thái người dùng');
        }

        return back()->with('error', 'Thay đổi trạng thái người dùng thất bại');
    }


    // Danh sách truyện
    public function fiction_list(Request $request)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $keyword = trim((string) $request->query('q', ''));
        $fictions = Fiction::with(['author', 'series'])
            ->withCount('like_fiction_history')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('fiction_name', 'like', "%{$keyword}%")
                        ->orWhereHas('author', function ($authorQuery) use ($keyword) {
                            $authorQuery->where('username', 'like', "%{$keyword}%");
                        });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

         return view('admin.profile.all_fictions', compact('fictions', 'keyword'));
    }

    // Danh sách series
    public function series_list(Request $request)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $keyword = trim((string) $request->query('q', ''));
        $allSeries = Series::with(['author'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('series_name', 'like', "%{$keyword}%")
                        ->orWhereHas('author', function ($authorQuery) use ($keyword) {
                            $authorQuery->where('username', 'like', "%{$keyword}%");
                        });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.profile.all_series', compact('allSeries', 'keyword'));
    }

    // Xử lý AJAX xóa series
    public function delete_series(string $series_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }

        $series = Series::whereId($series_id)
            ->firstOrFail();

        if ($series->image_link !== "default.jpeg") {
            Storage::disk('public')->delete($series->image_link);
        }

        if($series->delete())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa series.',
            ]);
    }

    // Xử lý AJAX xóa truyện
    public function delete_fiction(string $fiction_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }

        $fiction = Fiction::whereId($fiction_id)
            ->firstOrFail();

        if ($fiction->image_link !== "default.jpeg") {
            Storage::disk('public')->delete($fiction->image_link);
        }

        if($fiction->delete())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa truyện.',
            ]);
    }

    // Xử lý AJAX xóa chương truyện
    public function delete_chapter(string $chapter_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }

        $chapter = Chapter::whereId($chapter_id)->firstOrFail();

        if($chapter->delete())
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa chương.',
            ]);
    }

    // Xử lý AJAX xóa comment
    public function delete_comment(string $comment_id)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "user_moderator") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }

        $comment = ChapterComment::where('id', $comment_id)->firstOrFail();

        DB::transaction(function () use ($comment): void {
            $this->deleteCommentTree(ChapterComment::class, $comment->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa comment.',
        ]);
        
    }


    // Dành cho moderator
    // Danh sách các mod
    public function moderator_list(Request $request)
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "admin") {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }

        $keyword = trim((string) $request->query('q', ''));

        $moderators = Moderator::when($keyword !== '', function ($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%");
            })->withTrashed()->latest('created_at')->paginate(10)->withQueryString();

        $data = [
            'moderators' => $moderators,
            'moderator' => $moderator
        ];

        return view('admin.profile.all_moderators', $data);
    }


    // Thay đổi quyền của các mod khác
    public function change_mod_permission(Request $request, string $mod_id)
    {
        $permission = $request->permission ?? 'none';

        $moderator = Moderator::where('permission', '!=', 'admin')->findOrFail($mod_id);

        $moderator->permission = $permission;

        if($moderator->save())
        {
            return back()->with('success', 'Đã thay đổi vai trò của ' . $moderator->username . ' thành công');
        }

        return back()->with('error', 'Thay đổi vai trò thất bại');
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

    // =========================================================
    // Tạo tài khoản moderator (Chỉ quyền admin mới vào được)
    // Route: GET /admin/register
    // =========================================================
    public function showRegister()
    {
        $moderator = Auth::guard('moderator')->user();
        if ($moderator->permission !== "admin") {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập vào mục này');
        }
        return view('admin.create_moderator');
    }

    public function register(Request $request)
    {
        // 1. Xác thực dữ liệu đầu vào (Validation)
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'permission' => 'nullable'
        ], [
            // Custom thông báo lỗi bằng tiếng Việt nếu muốn
            'username.unique' => 'Tên người dùng này đã tồn tại.',
            'email.unique'    => 'Địa chỉ email này đã được đăng ký.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        // 2. Tạo User mới vào Database (Mã UUID và mật khẩu băm sẽ tự xử lý)
        Moderator::create([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Luôn băm mật khẩu bảo mật
            'permission' => $request->permission ?? 'none'
        ]);
        return redirect()->route('admin.moderator_list')->with('success', 'Tạo tài khoản moderator mới thành công');       
    }


    // =========================================================
    // Thay đổi thông tin moderator
    // Route: GET /admin/change_info
    // =========================================================
    public function showChangeInfo()
    {
        return view('admin.change_info');
    }

    public function change_info(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email|max:255',
            'new_password' => 'required|string|min:6'
        ], [
            // Custom thông báo lỗi bằng tiếng Việt nếu muốn
            'new_password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        $user = Auth::guard('moderator')->user();

        $exist_email = Moderator::whereEmail(trim($request->email))->where('id', '!=', $user->id)->exists();
        if($exist_email)
            return back()->with('error', 'Email này đã được người khác đăng ký');

        $user->email = trim($request->email);
        $user->password = Hash::make(trim($request->new_password));
        if($user->save())
            return redirect()->route('admin.dashboard')->with('success', 'Thay đổi thông tin thành công');

        return redirect()->route('admin.dashboard')->with('error', 'Thay đổi thông tin thất bại');
    }

    // Xóa/khôi phục tài khoản của moderator
    // POST: admin/toggle_moderator/{moderator_id}
    public function toggleModeratorDelete(string $moderator_id)
    {
        $user = Auth::guard('moderator')->user();
        if ($user->permission !== "admin") {
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);
        }
        $moderator = Moderator::withTrashed()->where('permission', '!=', 'admin')->whereId($moderator_id)->firstOrFail();

        $message = "";

        if($moderator->deleted_at)
        {
            $message = "Khôi phục tài khoản {$moderator->username} ";
            if($moderator->restore())
                return back()->with('success', $message . "thành công");
            return back()->with('error', $message . "thất bại");
        }

        $message = "Vô hiệu hóa tài khoản {$moderator->username} ";
        if($moderator->delete())
                return back()->with('success', $message . "thành công");
            return back()->with('error', $message . "thất bại");
        
    }


    // API: Lấy thông tin của moderator
    // POST: admin/get_moderator_info/{mod_id}
    public function getModerator(string $mod_id)
    {
        $user = Auth::guard('moderator')->user();
        if($user->permission !== 'admin')
            return response()->json(['message' => 'Không có quyền', 'success' => false], 403);

        $moderator = Moderator::withTrashed()->where('permission', '!=', 'admin')->whereId($mod_id)->firstOrFail();

        return response()->json([
            'moderator' => $moderator,
            'success' => true
        ]);
    }
}
