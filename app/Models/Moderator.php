<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moderator extends Authenticatable
{
    use HasUuids, SoftDeletes;

    /**
     * Tên bảng tương ứng trong Database
     */
    protected $table = 'moderators'; // 

    /**
     * Các thuộc tính có thể gán dữ liệu hàng loạt (Mass Assignable)
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'permission',
    ];

    /**
     * Các thuộc tính cần ẩn khi chuyển model thành dạng mảng hoặc JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(ModeratorPost::class, 'moderator_id');
    }

    // Tự động sinh ra thuộc tính ảo mang tên permission_name
    public function getPermissionNameAttribute()
    {
        if($this->deleted_at)
        {
            return "Đã xóa";
        }
        switch ($this->permission) {
            case 'admin':
                return 'Admin';
            case 'user_moderator':
                return 'Quản trị người dùng';
            case 'post_moderator':
                return 'Quản trị bài đăng';
            default:
                return 'Không có';
        }
    }
}
