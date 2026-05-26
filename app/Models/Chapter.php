<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasUuids;

    protected $table = 'chapters';

    protected $fillable = [
        'chapter_name',
        'chapter_order',
        'fiction_id',
        'content',
        'is_posted',
        'watch_count'
    ];

    public function fiction()
    {
        return $this->belongsTo(Fiction::class, 'fiction_id');
    }
    
    public function comments()
    {
        return $this->belongsTo(ChapterComment::class, 'chapter_id');
    }

    public function like_chapter_history()
    {
        return $this->hasMany(LikeChapterHistory::class, 'chapter_id');
    }
}
