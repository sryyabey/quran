<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class NoteShareLink extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'title',
        'visibility',
        'expires_at',
        'revoked_at',
        'payload',
        'access_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'access_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon && $this->expires_at->isPast();
    }

    public function isRevoked(): bool
    {
        return ! is_null($this->revoked_at);
    }
}
