<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'size', 'languages', 'properties', 'active'];

    protected $casts = [
        'languages' => 'json',
        'properties' => 'json',
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
