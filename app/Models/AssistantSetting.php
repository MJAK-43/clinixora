<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistantSetting extends Model
{
    public const MODE_MVP = 'mvp';

    public const MODE_LLM = 'llm';

    protected $fillable = [
        'mode',
        'assistant_enabled',
        'require_confirmation_writes',
        'llm_provider',
        'llm_model',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'assistant_enabled' => 'boolean',
            'require_confirmation_writes' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'mode' => self::MODE_MVP,
            'assistant_enabled' => true,
            'require_confirmation_writes' => true,
        ]);
    }
}
