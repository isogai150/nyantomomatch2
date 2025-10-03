<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function document()
    {
        return $this->hasOne(TransferDocument::class);
    }
}
