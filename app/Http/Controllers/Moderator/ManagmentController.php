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
use App\Models\Series;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ManagmentController extends Controller
{
    public function dashboard()
    {
        return view('admin.profile.dashboard');
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
        $users = User::when($keyword !== '', function ($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%");
            })->latest('created_at')->paginate(10)->withQueryString();


        $data = [
            'users' => $users,
            'keyword' => $keyword
        ];

        return view('admin.profile.all_users', $data);
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
            return;
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
            return;
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
            return;
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
            return;
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
            })->latest('created_at')->paginate(10)->withQueryString();

        $data = [
            'moderators' => $moderators
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
}
