<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'comic_id',
        'admin_id',
        'status',
        'requested_at',
        'approved_at',
        'borrowed_at',
        'due_at',
        'returned_at',
    ];

    /**
     * Cast date/datetime attributes to Carbon instances
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at'  => 'datetime',
        'borrowed_at'  => 'datetime',
        'due_at'       => 'datetime',
        'returned_at'  => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Status constants
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';
    public const STATUS_TERLAMBAT = 'terlambat';
    public const STATUS_REJECTED = 'rejected';

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Scopes
    public function scopeActiveForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->where('status', self::STATUS_DIPINJAM);
    }

    // Helpers
    public function isOverdue(): bool
    {
        // safe checks (returned_at may be null)
        return $this->due_at && $this->returned_at && $this->returned_at->gt($this->due_at);
    }

    public function isCurrentlyBorrowed(): bool
    {
        return $this->status === self::STATUS_DIPINJAM;
    }
}
