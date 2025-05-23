<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Support\Collection;

class QRs extends Model
{
    use SoftDeletes;

    protected $table = '_q_r';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'client_id',
        'url_qrcode',
        'link',
        'visits_count',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'client_id' => 'integer',
        'url_qrcode' => 'string',
        'link' => 'string',
        'visits_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function searcher(): array
    {
        $isClient = request() && str_starts_with(request()->route()?->getName() ?? '', 'client.');
        $products = Products::select(['id', 'name'])->get();

        $filters = [
            'product' => [
                'type' => 'suggestion',
                'data' => $products->map(fn ($product) => json_decode(json_encode(['id' => $product->id, 'value' => $product->name]))),
            ],
        ];

        if (! $isClient) {
            $clients = Clients::select(['id', 'legal_name'])->get();
            $filters = [
                'client' => [
                    'type' => 'select',
                    'data' => $clients->map(fn ($client) => json_decode(json_encode(['id' => $client->id, 'value' => $client->legal_name]))),
                ],
            ] + $filters;
        }

        return $filters;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
}
