<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'client_id',
        'description',
        'brand',
        'model',
        'origin',
        'created_at',
        'updated_at',
    ];

    public function getFormData(): array
    {
        return [
            'client' => 'select',
            'name' => 'text',
            'brand' => 'text',
            'model' => 'text',
            'origin' => 'text',
            'description' => 'textarea',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(Files::class, 'product_id', 'id');
    }

    public function qrs(): HasOne
    {
        return $this->hasOne(QRs::class, 'product_id');
    }
}
