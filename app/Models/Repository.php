<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'host',
        'languages',
        'properties',
        'visibility',
        'active',
    ];

    protected $casts = [
        'languages' => 'array',
        'properties' => 'json'
    ];

    public static function getTotalSize()
    {
        return Repository::sum('size');
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }
}
