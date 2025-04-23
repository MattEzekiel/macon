<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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

    public static function searcher(array $request = []): array
    {
        $productsQuery = Products::select(['name', 'brand', 'model', 'origin']);

        if (isset($request['deleted']) && $request['deleted'] == '1') {
            $productsQuery->onlyTrashed();
        } elseif (! isset($request['deleted']) || $request['deleted'] == '2') {
            $productsQuery->withTrashed();
        }

        $products = $productsQuery->get();
        $clients = Clients::select(['id', 'legal_name'])->get();

        return [
            'client' => [
                'type' => 'select',
                'data' => $clients,
            ],
            'name' => [
                'type' => 'suggestion',
                'data' => $products->map(fn ($product) => json_decode(json_encode(['id' => $product->id, 'value' => $product->name]))),
            ],
            'brand' => [
                'type' => 'suggestion',
                'data' => $products->map(fn ($product) => json_decode(json_encode(['id' => $product->id, 'value' => $product->brand]))),
            ],
            'model' => [
                'type' => 'suggestion',
                'data' => $products->map(fn ($product) => json_decode(json_encode(['id' => $product->id, 'value' => $product->model]))),
            ],
            'origin' => [
                'type' => 'suggestion',
                'data' => $products->map(fn ($product) => json_decode(json_encode(['id' => $product->id, 'value' => $product->origin]))),
            ],
            'deleted' => [
                'type' => 'select',
                'data' => Collection::make([
                    ['id' => '0', 'value' => __('general.select_no')],
                    ['id' => '1', 'value' => __('general.select_yes')],
                    ['id' => '2', 'value' => __('general.select_all')],
                ]),
            ],
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($product) {
            $product->files()->each(function ($file) {
                $file->delete();
            });

            $product->qrs()->each(function ($qr) {
                $qr->delete();
            });
        });

        static::restoring(function ($product) {
            $product->files()->withTrashed()->each(function ($file) {
                $file->restore();
            });

            $product->qrs()->withTrashed()->each(function ($qr) {
                $qr->restore();
            });
        });
    }

    public function files(): HasMany
    {
        return $this->hasMany(Files::class, 'product_id', 'id');
    }

    public function qrs(): HasOne
    {
        return $this->hasOne(QRs::class, 'product_id');
    }

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
}
