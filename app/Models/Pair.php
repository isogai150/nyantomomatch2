<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

    // 配列をまとめてモデルに登録・更新する仕組み
    // 一括代入で値を入れてOKなカラム
    protected $fillable = [
        'userA_id',
        'userB_id',
        'post_id',
    ];

    public function userA()
    {
        return $this->belongsTo(User::class, 'userA_id');
    }

    public function userB()
    {
        return $this->belongsTo(User::class, 'userB_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'pair_id');
    }

    public function messageReports()
    {
        return $this->hasMany(MessageReport::class, 'pair_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
