<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Files extends Model
{
    protected $table = 'files';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'file_url',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
}
