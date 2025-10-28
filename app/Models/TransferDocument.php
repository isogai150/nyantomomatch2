<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transfer_id',
        'pair_id',
        'conditions_agreed_at',
        'contract_signed_name',
        'contract_signed_date',
        'contract_submitted_at',
    ];

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'pair_id');
    }
}
