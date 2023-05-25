<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'properties',
    ];

    protected $appends = [
        'selector'
    ];

    protected $casts = [
        'properties' => 'json'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_categories');
    }
    
    public function getSelectorAttribute()
    {
        if (isset($this->properties["html_selector"])) {
            return $this->properties["html_selector"];
        }
        return null;
    }
}
