<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_name',
        'external_link',
        'external_image_link',
        'framework',
        'excerpt',
        'position',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json'
    ];
}
