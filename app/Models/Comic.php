<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comic extends Model
{
    use HasFactory;

    protected $fillable = ['title','category_id','description','stock','cover_path'];

    protected $casts = [
        'stock' => 'integer',
    ];

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
}