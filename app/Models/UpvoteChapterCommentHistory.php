<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UpvoteChapterCommentHistory extends Model
{
    //
    use HasUuids;

    protected $table = 'upvote_chapter_comment_history';

    protected $fillable = [
        'comment_id',
        'user_id',
        'count'
    ];

    public function comment()
    {
        $this->belongsTo(ChapterComment::class, 'comment_id');
    }

    public function user()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
