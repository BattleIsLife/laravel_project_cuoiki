<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChapterComment extends Model
{
    use HasUuids;

    protected $table = 'chapter_comments';

    protected $fillable = [
        'chapter_id',
        'user_id',
        'content',
        'parent_comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function upvote_history()
    {
        return $this->hasMany(UpvoteChapterCommentHistory::class, 'comment_id');
    }

    public function child_comment()
    {
        return $this->hasMany(ChapterComment::class, 'parent_comment');
    }
}
