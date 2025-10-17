<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'userA_id',
        'userB_id',
        'post_id',
        'transfer_document_id',
        'confirmed_at',
        'created_at',
        'updated_at',
    ];

    // 投稿者（譲渡する側）
    public function userA()
    {
        return $this->belongsTo(User::class, 'userA_id');
    }

    // 支払者（譲渡を受ける側）
    public function userB()
    {
        return $this->belongsTo(User::class, 'userB_id');
    }

    // 対象の投稿
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 譲渡資料（1対1）
    public function document()
    {
        return $this->hasOne(TransferDocument::class);
    }
}
