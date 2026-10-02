<?php

declare(strict_types=1);

namespace App\Settlement\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function read(string $key, mixed $default = null): mixed
    {
        $row = static::query()->where('key', $key)->first();

        if ($row === null || $row->getRawOriginal('value') === null) {
            return $default;
        }

        return $row->value;
    }

    public static function write(string $key, mixed $value, ?int $actorId = null): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'updated_by' => $actorId]
        );
    }
}
