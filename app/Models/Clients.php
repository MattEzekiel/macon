<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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
        'deleted_at' => 'datetime',
    ];

    public static function searcher(array $request = []): array
    {
        $clientQuery = Clients::select(['id', 'legal_name']);

        if (isset($request['deleted']) && $request['deleted'] == '1') {
            $clientQuery->onlyTrashed();
        } elseif (! isset($request['deleted']) || $request['deleted'] == '2') {
            $clientQuery->withTrashed();
        }

        $clients = $clientQuery->get();

        return [
            'client' => [
                'type' => 'select',
                'data' => $clients,
            ],
            'deleted' => [
                'type' => 'select',
                'data' => Collection::make([
                    ['id' => '0', 'value' => 'No'],
                    ['id' => '1', 'value' => 'Si'],
                    ['id' => '2', 'value' => 'Todos'],
                ]),
            ],
        ];
    }

    public static function getFormData(): array
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

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($client) {
            $client->products()->each(function ($product) {
                $product->delete();
            });

            $client->qrs()->each(function ($qr) {
                $qr->delete();
            });

            $client->files()->each(function ($file) {
                $file->delete();
            });
        });

        static::restoring(function ($client) {
            $client->products()->withTrashed()->each(function ($product) {
                $product->restore();
            });

            $client->qrs()->withTrashed()->each(function ($qr) {
                $qr->restore();
            });

            $client->files()->withTrashed()->each(function ($file) {
                $file->restore();
            });
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'client_id');
    }

    public function qrs(): HasMany
    {
        return $this->hasMany(QRs::class, 'client_id');
    }

    public function files(): HasManyThrough
    {
        return $this->hasManyThrough(
            Files::class,
            Products::class,
            'client_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
