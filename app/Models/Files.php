<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model
{
    use SoftDeletes;

    protected $table = 'files';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'file_url',
        'original_file_name',
        'file_name',
        'file_size'
    ];

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Products::class, 'files', 'product_id', 'id');
    }
}
