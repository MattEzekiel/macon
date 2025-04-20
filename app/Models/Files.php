<?php

namespace App\Models;

use App\Traits\FileSizeFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model
{
    use FileSizeFormatter, SoftDeletes;

    protected $table = 'files';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'file_url',
        'original_file_name',
        'file_name',
        'file_size',
    ];

    public static function searcher(array $request = []): array
    {
        $filesQuery = Files::select(['file_name', 'original_file_name', 'created_at'])
            ->with(['product.client']);

        if (isset($request['deleted']) && $request['deleted'] == '1') {
            $filesQuery->onlyTrashed();
        } elseif (!isset($request['deleted']) || $request['deleted'] == '2') {
            $filesQuery->withTrashed();
        }

        $files = $filesQuery->get();
        $clients = Clients::select(['id', 'legal_name'])->get();
        $products = Products::select(['id', 'name'])->get();

        return [
            'client' => [
                'type' => 'select',
                'data' => $clients,
            ],
            'product' => [
                'type' => 'select',
                'data' => $products,
            ],
            'file_name' => [
                'type' => 'suggestion',
                'data' => $files->map(fn($file) => json_decode(json_encode(['id' => $file->id, 'value' => $file->file_name]))),
            ],
            'original_file_name' => [
                'type' => 'suggestion',
                'data' => $files->map(fn($file) => json_decode(json_encode(['id' => $file->id, 'value' => $file->original_file_name]))),
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
