<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Comic extends Model
{
    use HasFactory;

    // include both names in fillable so mass assignment tetap aman
    protected $fillable = [
        'title',
        'slug',
        'description', // existing column in your DB
        'synopsis',    // virtual (mapped) attribute
        'author',
        'publisher',
        'cover',       // virtual (mapped) attribute
        'cover_path',  // existing column in your DB
        'category_id',
        'status',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    // Keep existing helper method
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'comic_genre');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    // --- Compatibility accessors/mutators ---

    // allow $comic->synopsis to read description (if synopsis column not present)
    public function getSynopsisAttribute()
    {
        return $this->attributes['synopsis'] ?? $this->attributes['description'] ?? null;
    }

    // setting synopsis writes to description (and synopsis if exists)
    public function setSynopsisAttribute($value)
    {
        $this->attributes['description'] = $value;
        $this->attributes['synopsis'] = $value;
    }

    // allow $comic->cover to read cover_path
    public function getCoverAttribute()
    {
        return $this->attributes['cover'] ?? $this->attributes['cover_path'] ?? null;
    }

    // setting cover writes to cover_path (and cover if exists)
    public function setCoverAttribute($value)
    {
        $this->attributes['cover_path'] = $value;
        $this->attributes['cover'] = $value;
    }

    // auto-generate slug if not provided and title exists
    protected static function booted()
    {
        static::creating(function ($comic) {
            if (empty($comic->slug) && !empty($comic->title)) {
                $comic->slug = Str::slug($comic->title);
            }
        });
    }
}
