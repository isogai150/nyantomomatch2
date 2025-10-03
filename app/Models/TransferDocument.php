<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDocument extends Model
{
    use HasFactory;

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
