<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pair_id',
        'message_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
