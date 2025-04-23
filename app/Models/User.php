<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function searcher(array $request = []): array
    {
        $userQuery = User::select(['id', 'name', 'email']);

        if (isset($request['deleted']) && $request['deleted'] == '1') {
            $userQuery->onlyTrashed();
        } elseif (! isset($request['deleted']) || $request['deleted'] == '2') {
            $userQuery->withTrashed();
        }

        $users = $userQuery
            ->with('client')
            ->get();

        $clients = Clients::select('id', 'legal_name')->get();

        return [
            'name' => [
                'type' => 'suggestion',
                'data' => $users->map(fn ($file) => json_decode(json_encode(['id' => $file->id, 'value' => $file->name]))),
            ],
            'client' => [
                'type' => 'select',
                'data' => $clients->map(fn ($client) => json_decode(json_encode(['id' => $client->id, 'value' => $client->legal_name]))),
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

    public function getFormData(): array
    {
        return [
            'name' => 'text',
            'email' => 'email',
            'client' => 'select',
            'password' => 'password',
            'confirm_password' => 'password',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id', 'id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
