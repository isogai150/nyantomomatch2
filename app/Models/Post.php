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

    //アクセサ
    public function getStatusLabelAttribute()
{
    return match ($this->status) {
        0 => '里親募集中',
        1 => 'お見合い中',
        2 => '譲渡成立',
        default => '不明',
    };
}

public function getStatusClassAttribute()
{
    return match ($this->status) {
        0 => 'status-available',
        1 => 'status-talking',
        2 => 'status-matched',
        default => 'status-unknown',
    };
}

}