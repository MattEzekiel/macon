<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'client_id',
        'url_file',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class);
    }
}
