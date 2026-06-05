<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAction extends Model
{
    protected $fillable = [
        'action_key',
        'module',
        'label',
        'description',
        'risk_level',
        'requires_confirmation',
        'is_enabled',
        'handler_class',
    ];

    protected function casts(): array
    {
        return [
            'requires_confirmation' => 'boolean',
            'is_enabled' => 'boolean',
        ];
    }
}
