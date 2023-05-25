<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'image_name',
        'external_link',
        'external_image_link',
        'subtitle',
        'position',
        'properties',
    ];

    protected $appends = [
        'selector'
    ];

    protected $casts = [
        'properties' => 'json'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }

    public function getSelectorAttribute()
    {
        if ($this->categories) {
            $category = $this->categories[0];
            return $category->selector;
        }
    }
}
