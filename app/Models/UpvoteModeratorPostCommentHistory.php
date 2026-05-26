<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UpvoteModeratorPostCommentHistory extends Model
{
    //
    use HasUuids;


    protected $table = 'upvote_moderator_post_comment_history';

    protected $fillable = [
        'comment_id',
        'user_id',
        'count'
    ];

    public function comment()
    {
        $this->belongsTo(ModeratorPostComment::class, 'comment_id');
    }

    public function user()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
