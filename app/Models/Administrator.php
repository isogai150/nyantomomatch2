<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrator extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;


    // ガード名
    protected $guard = 'admin';

    protected $fillable = ['name', 'email', 'password', 'image', ];
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['deleted_at'];
}
