<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Book extends Model
{
    use HasFactory;
    use AsSource;

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


    public function reorderPositions()
    {
        $oldPosition = $this->getOriginal('position');
        $newPosition = $this->position;
        if ($oldPosition === $newPosition) {
            return $this;
        }
        
        $books = Book::where('position', '>=', $newPosition)->orderBy('position')->get();

        foreach ($books as $book) {
            if ($this->id == $book->id) {
                continue;
            }

            $newPosition++;
            $book->position = $newPosition;
            $book->save();
        }

        return $this;
    }

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
