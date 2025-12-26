<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_admin',
        'gender',
        'bio',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function borrowings()
    {
        return $this->hasMany(\App\Models\Borrowing::class);
    }

    public function processedBorrowings()
    {
        return $this->hasMany(Borrowing::class, 'admin_id');
    }

    // Jika kamu ingin menyimpan genre favorit via relasi many-to-many
    public function genres()
    {
        return $this->belongsToMany(\App\Models\Genre::class);
    }

    public function initials()
    {
        $name = $this->name ?? '';
        $parts = preg_split('/\s+/', trim($name));
        if (count($parts) === 0) return strtoupper(substr($name, 0, 1));
        if (count($parts) === 1) return strtoupper(substr($parts[0], 0, 2));
        return strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
    }
}
