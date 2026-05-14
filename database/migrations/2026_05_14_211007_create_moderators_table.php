<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('moderators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255)->nullable(false);
            $table->enum('permission', [
                'admin', // Tạo moderator khác, thống kê, báo cáo, xem các mục còn lại nhưng không được làm gì, hoặc là xóa tài khoản mod
                'user_moderator', // Quản lý người dùng: cấm cửa người dùng, xóa comment của người dùng nếu như thấy có bình luận khiếm nhã
                'post_moderator', // Quản lý đăng bài post của moderator (cập nhật mới, v.v)
                'none' // Không có quyền gì cả, đang trong trạng thái chờ được phân việc hoặc vừa bị tước quyền
                // Tất cả có thể xem thống kê ngày hôm nay (nếu có)
            ])->default('none');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderators');
    }
};
