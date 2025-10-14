<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'image_path',
    ];
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
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id')
            ->withTimestamps();
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

    // アクセサ //

    // マイページ：ユーザーステータス
    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            0 => '一般ユーザー',
            1 => '投稿権限ユーザー',
        };
    }

    // マイページ：自己紹介文
    // 全文のdescriptionを取得
    protected function fullDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->description ?? '',
        );
    }

    // 短縮版のdescriptionを取得（最初の句点まで、または60文字）
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                $description = $this->description ?? '';

                // 句点の位置を検索
                $pos = mb_strpos($description, '。');

                if ($pos !== false) {
                    // 句点が見つかった場合は句点まで（句点を含む）
                    return mb_substr($description, 0, $pos + 1);
                } else {
                    // 句点がない場合は60文字まで
                    return mb_substr($description, 0, 60);
                }
            }
        );
    }
}
