<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class PageContent extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'page_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'html_id',
        'name',
        'key',
        'view',
        'content',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json'
    ];
}
