<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    public $timestamps = false;
    protected $table = 'password_reset_tokens';
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
    protected $casts = [
        'email' => 'string',
        'token' => 'string',
        'created_at' => 'datetime',
    ];
}
