<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pair extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'userA_id',
        'userB_id',
        'post_id',
        'transfer_status',
        'agreed_user_id',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pair) {
            if (!$pair->isForceDeleting()) {
                $pair->messages()->delete();
            }
        });

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

    public function transferDocument()
    {
        return $this->hasOne(TransferDocument::class, 'pair_id');
    }
}
