<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;

// =========================================================
    // 自分の投稿一覧表示機能での追記
    protected $fillable = ['post_id', 'image_path'];

// =========================================================

    // リレーション：画像 → 投稿（多対1）
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
