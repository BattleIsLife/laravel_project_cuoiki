<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fiction extends Model
{
    use HasUuids;

    protected $table = "fictions";

    protected $fillable = [
        'fiction_name',
        'user_id',
        'series_id',
        'description'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'fiction_id');
    }

    public function like_fiction_history()
    {
        return $this->hasMany(LikeFictionHistory::class, 'fiction_id');
    }
}
