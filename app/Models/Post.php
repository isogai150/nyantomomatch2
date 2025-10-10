<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function videos()
    {
        return $this->hasMany(PostVideo::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function pairs()
    {
        return $this->hasMany(Pair::class);
    }

    //アクセサ
    // status
    public function getStatusLabelAttribute()
{
    return match ($this->status) {
        0 => '里親募集中',
        1 => 'お見合い中',
        2 => '譲渡成立',
        default => '不明',
    };
}

// status
public function getStatusClassAttribute()
{
    return match ($this->status) {
        0 => 'status-available',
        1 => 'status-talking',
        2 => 'status-matched',
        default => 'status-unknown',
    };
}

//年齢
public function getUnitAgeAttribute()
{
    return $this->age .'歳';
}

//性別
public function getGenderClassAttribute()
{
    return match ($this->gender) {
        0 => '未入力',
        1 => 'オス',
        2 => 'メス',
        default => '未入力',
    };
}

// 費用
public function getCostClassAttribute()
{
    return $this->cost .'円';
}



// ====================自分の投稿一覧表示機能：関係情報ここから追記====================

// フィールド指定（今後の安全なデータ保存のため）
protected $fillable = [
    'user_id',
    'title',
    'age',
    'gender',
    'region',
    'status',
    'cost',
];

// created_at などの日付フィールドをCarbonで扱いやすくする
protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

// ==================== ここまで追記 ====================
}