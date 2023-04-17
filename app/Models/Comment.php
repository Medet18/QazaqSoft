<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_for_post',
        'post_id',
    ];

    public function posts()
    {
        return $this->belongsTo(Post::class);
    }
}
