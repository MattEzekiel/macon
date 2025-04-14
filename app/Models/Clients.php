<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use SoftDeletes;

    public $incrementing = true;

    public $timestamps = true;

    protected $table = 'clients';

    protected $primaryKey = 'id';

    protected $fillable = [
        'legal_name',
        'tax_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'legal_address',
    ];

    protected $casts = [
        'id' => 'integer',
        'legal_name' => 'string',
        'tax_id' => 'string',
        'contact_name' => 'string',
        'contact_email' => 'string',
        'contact_phone' => 'string',
        'legal_address' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFormData(): array
    {
        return [
            'legal_name' => 'text',
            'tax_id' => 'text',
            'contact_name' => 'text',
            'contact_email' => 'email',
            'contact_phone' => 'text',
            'legal_address' => 'text',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'client_id');
    }

    public function qrs(): HasMany
    {
        return $this->hasMany(QRs::class, 'client_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
