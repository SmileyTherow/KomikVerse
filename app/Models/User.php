<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema; // <- tambah import Schema

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
        'status',
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

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Scope untuk mengecualikan user yang merupakan admin.
     *
     * Gunakan User::nonAdmins() pada query untuk menampilkan hanya user biasa.
     */
    public function scopeNonAdmins($query)
    {
        // Asumsi: tabel users punya kolom is_admin. Jika kolom selalu ada,
        // kamu bisa langsung return $query->where('is_admin', false);
        // Namun untuk safety bila migrasi berbeda, kita cek dulu column via Schema.
        try {
            if (Schema::hasColumn($this->getTable(), 'is_admin')) {
                return $query->where('is_admin', false);
            }

            if (Schema::hasColumn($this->getTable(), 'role')) {
                return $query->where('role', '!=', 'admin');
            }
        } catch (\Throwable $e) {
            // Jika pengecekan Schema gagal (mis. saat bootstrap), fallback ke where is_admin = false
            return $query->where('is_admin', false);
        }

        // fallback generik
        return $query->where('is_admin', false);
    }

    public function likedComics(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Comic::class, 'comic_likes', 'user_id', 'comic_id')->withTimestamps();
    }
}