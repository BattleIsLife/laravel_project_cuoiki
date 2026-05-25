<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasUuids;

    protected $table = "series";

    protected $fillable = [
        'series_name',
        'user_id',
        'description'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fictions()
    {
        return $this->hasMany(Fiction::class, 'series_id');
    }

    public function chapters()
    {
        // Tham số: (Model_Đích, Model_Trung_Gian, Khóa_Ngoại_Trung_Gian, Khóa_Ngoại_Đích)
        return $this->hasManyThrough(Chapter::class, Fiction::class, 'series_id', 'fiction_id');
    }
}
