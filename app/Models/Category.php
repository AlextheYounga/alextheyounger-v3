<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'name',
        'type',
        'position',
        'properties',
        'active',
    ];

    protected $appends = [
        'selector'
    ];

    protected $casts = [
        'properties' => 'json'
    ];

    public function categories()
    {
        return $this->hasMany(Book::class);
    }
    
    public function getSelectorAttribute()
    {
        if (isset($this->properties["html_selector"])) {
            return $this->properties["html_selector"];
        }
        return null;
    }

    public function reorderPositions()
    {
        $oldPosition = $this->getOriginal('position');
        $newPosition = $this->position;
        if ($oldPosition === $newPosition) {
            return $this;
        }
        
        $categories = Category::where('position', '>=', $newPosition)->orderBy('position')->get();

        foreach ($categories as $category) {
            if ($this->id == $category->id) {
                continue;
            }

            $newPosition++;
            $category->position = $newPosition;
            $category->save();
        }

        return $this;
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }
}
