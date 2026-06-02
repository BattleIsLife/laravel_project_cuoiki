<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ModeratorPostComment extends Model
{
    //
    use HasUuids;

    protected $table = 'moderator_post_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'parent_comment'
    ];

    public function post()
    {
        return $this->belongsTo(ModeratorPost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function upvote_history()
    {
        return $this->hasMany(UpvoteModeratorPostCommentHistory::class, 'comment_id');
    }
}
