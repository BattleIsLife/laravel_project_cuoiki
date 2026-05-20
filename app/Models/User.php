<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasUuids;

    /**
     * Tên bảng tương ứng trong Database
     */
    protected $table = 'users'; // 

    /**
     * Các thuộc tính có thể gán dữ liệu hàng loạt (Mass Assignable)
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'blocked_until',
    ];

    /**
     * Các thuộc tính cần ẩn khi chuyển model thành dạng mảng hoặc JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu cho các thuộc tính (Casting)
     */
    protected $casts = [
        'blocked_until' => 'datetime',
    ];

    public function series()
    {
        return $this->hasMany(Series::class, 'user_id');
    }

    public function fictions()
    {
        return $this->hasMany(Fiction::class, 'user_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'user_id');
    }

    public function chapter_comments()
    {
        return $this->hasMany(ChapterComment::class, 'user_id');
    }
}