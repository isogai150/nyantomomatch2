<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVideo extends Model
{
    use HasFactory;

// =========================================================
    // 自分の投稿一覧表示機能での追記
    protected $fillable = ['post_id', 'video_path'];

// =========================================================

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

