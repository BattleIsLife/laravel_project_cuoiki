<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeFictionHistory extends Model
{
    protected $table="like_fiction_history";

    protected $fillable = [
        'user_id',
        'fiction_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function fiction()
    {
        require $this->belongsTo(Fiction::class, 'fiction_id');
    }
}
