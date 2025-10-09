<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

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
        return $this->hasMany(Message::class);
    }

    public function messageReports()
    {
        return $this->hasMany(MessageReport::class);
    }

    public function posts()
    {
        return $this->belongsTo(Post::class);
    }
}
