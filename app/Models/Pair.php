<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pair extends Model
{
    use HasFactory, SoftDeletes;

    // 配列をまとめてモデルに登録・更新する仕組み
    // 一括代入で値を入れてOKなカラム
    protected $fillable = [
        'userA_id',
        'userB_id',
        'post_id',
        'transfer_status',
    ];

    // 日付として扱うカラムを指定
    protected $dates = ['deleted_at'];

    // Pairが削除されたときにメッセージも論理削除
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pair) {
            // 論理削除の場合のみメッセージも論理削除
            if (!$pair->isForceDeleting()) {
                $pair->messages()->delete();
            }
        });

        // Pairが復元されたときにメッセージも復元
        static::restoring(function ($pair) {
            $pair->messages()->withTrashed()->restore();
        });
    }


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
