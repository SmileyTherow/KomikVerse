<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Comic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'synopsis',
        'author',
        'publisher',
        'cover',
        'cover_path',
        'category_id',
        'status',
        'stock',
        'page_count',
        'language',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    public function genres()
    {
        return $this->belongsToMany(\App\Models\Genre::class, 'comic_genre', 'comic_id', 'genre_id');
    }

    public function likes()
    {
        return $this->belongsToMany(\App\Models\User::class, 'comic_likes', 'comic_id', 'user_id')->withTimestamps();
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function getSynopsisAttribute()
    {
        return $this->attributes['synopsis'] ?? $this->attributes['description'] ?? null;
    }

    public function setSynopsisAttribute($value)
    {
        $this->attributes['description'] = $value;
        $this->attributes['synopsis'] = $value;
    }

    public function getCoverAttribute()
    {
        return $this->attributes['cover'] ?? $this->attributes['cover_path'] ?? null;
    }

    public function setCoverAttribute($value)
    {
        $this->attributes['cover_path'] = $value;
        $this->attributes['cover'] = $value;
    }

    protected static function booted()
    {
        static::creating(function ($comic) {
            if (empty($comic->slug) && !empty($comic->title)) {
                $comic->slug = Str::slug($comic->title);
            }
        });
    }

    public function getLanguageLabelAttribute(): ?string
    {
        $map = [
            'ID' => 'Bahasa Indonesia',
            'EN' => 'English',
            'JP' => '日本語',
            'KR' => '한국어',
            'CN' => '中文',
        ];

        $code = $this->language ?? null;
        if (!$code) return null;
        return $map[$code] ?? $code;
    }

    public function scopeSearch($query, $term)
    {
        $term = "%{$term}%";
        return $query->where('title', 'like', $term)
            ->orWhereHas('category', fn($q) => $q->where('name', 'like', $term))
            ->orWhereHas('genres', fn($q) => $q->where('name', 'like', $term));
    }
}
