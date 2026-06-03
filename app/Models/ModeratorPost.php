<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ModeratorPost extends Model
{
    use HasUuids;

    protected $table = "moderator_posts";

    protected $fillable = [
        'title',
        'moderator_id',
        'description'
    ];

    public function moderator()
    {
        return $this->belongsTo(Moderator::class, 'moderator_id')->withTrashed();
    }

    public function comments()
    {
        return $this->hasMany(ModeratorPostComment::class, 'post_id');
    }
}
