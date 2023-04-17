<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'comment',
        'photo_for_post',
    ];
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
