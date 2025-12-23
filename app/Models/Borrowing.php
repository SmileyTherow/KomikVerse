<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comic_id',
        'admin_id',
        'status',
        'requested_at',
        'approved_at',
        'borrowed_at',
        'due_at',
        'returned_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comic()
    {
        return $this->belongsTo(\App\Models\Comic::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

    public function isActive()
    {
        return $this->status === 'dipinjam';
    }
}
