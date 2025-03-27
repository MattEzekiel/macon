<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QRs extends Model
{
    protected $table = '_q_r';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'client_id',
        'url_qrcode',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class);
    }
}
