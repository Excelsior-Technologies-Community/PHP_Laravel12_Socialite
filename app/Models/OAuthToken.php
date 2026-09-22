<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OAuthToken extends Model
{
    use HasFactory;

    protected $table = 'oauth_tokens';

    protected $fillable = [
        'user_id',
        'provider',
        'access_token',
        'refresh_token',
        'token_secret',
        'scopes',
        'expires_at',
    ];

    protected $casts = [
        'scopes' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Accessor for decrypted access_token
     */
    public function getAccessTokenAttribute($value): ?string
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Mutator for encrypted access_token
     */
    public function setAccessTokenAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['access_token'] = Crypt::encryptString($value);
        }
    }

    /**
     * Accessor for decrypted refresh_token
     */
    public function getRefreshTokenAttribute($value): ?string
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Mutator for encrypted refresh_token
     */
    public function setRefreshTokenAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['refresh_token'] = Crypt::encryptString($value);
        } else {
            $this->attributes['refresh_token'] = null;
        }
    }

    /**
     * Accessor for decrypted token_secret
     */
    public function getTokenSecretAttribute($value): ?string
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Mutator for encrypted token_secret
     */
    public function setTokenSecretAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['token_secret'] = Crypt::encryptString($value);
        } else {
            $this->attributes['token_secret'] = null;
        }
    }

    /**
     * Belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) return false;
        return $this->expires_at->isPast();
    }

    /**
     * Check if token expires within N minutes
     */
    public function expiresSoon(int $minutes = 5): bool
    {
        if (!$this->expires_at) return false;
        return $this->expires_at->subMinutes($minutes)->isPast();
    }
}
