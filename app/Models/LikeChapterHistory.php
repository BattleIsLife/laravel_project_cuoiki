<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeChapterHistory extends Model
{
    //
    protected $table="like_fiction_history";

    protected $fillable = [
        'user_id',
        'chapter_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function chapter()
    {
        require $this->belongsTo(Fiction::class, 'chapter_id');
    }
}
