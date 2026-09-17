<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_name',
        'browser',
        'platform',
        'last_activity',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'last_activity' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Get the user who owns this session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check whether this session has been revoked.
     */
    public function isRevoked(): bool
    {
        return !is_null($this->revoked_at);
    }
}