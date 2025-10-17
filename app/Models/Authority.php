<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Authority extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'reason',
        'status',
    ];

    // ステータス定数
    const STATUS_PENDING = 0;  // 申請中
    const STATUS_APPROVED = 1; // 承認
    const STATUS_REJECTED = 2; // 却下

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
