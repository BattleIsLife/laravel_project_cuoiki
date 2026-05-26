<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moderator extends Model
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
}
