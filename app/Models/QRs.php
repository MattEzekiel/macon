<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QRs extends Model
{
    use SoftDeletes;

    protected $table = '_q_r';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'client_id',
        'url_qrcode',
        'visits_count',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'client_id' => 'integer',
        'url_qrcode' => 'string',
        'visits_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
}
