<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime', // deleted_atをdatetimeとしてキャスト
    ];

    // 投稿
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // お気に入り（中間テーブル経由）
    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'favorites')->withTimestamps();
    }

    // メッセージ
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // DMペア
    public function pairsAsUserA()
    {
        return $this->hasMany(Pair::class, 'userA_id');
    }
    public function pairsAsUserB()
    {
        return $this->hasMany(Pair::class, 'userB_id');
    }

    // 権限申請
    public function authorities()
    {
        return $this->hasMany(Authority::class);
    }

    // 通報（通報者）
    public function messageReports()
    {
        return $this->hasMany(MessageReport::class, 'user_id');
    }
    public function postReports()
    {
        return $this->hasMany(PostReport::class, 'user_id');
    }

    // 譲渡成立
    public function transfersAsA()
    {
        return $this->hasMany(Transfer::class, 'userA_id');
    }
    public function transfersAsB()
    {
        return $this->hasMany(Transfer::class, 'userB_id');
    }

    //アクセサ
    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            0 => '一般ユーザー',
            1 => '投稿権限ユーザー',
        };
    }
}
