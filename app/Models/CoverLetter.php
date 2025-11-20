<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class CoverLetter extends Model
{
    use HasFactory;
    use AsSource;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->hash)) {
                $len = 8;
                $randomHash = substr(hash('sha256', openssl_random_pseudo_bytes(22)), -$len);
                $model->hash = $randomHash;
            }
        });
    }

    protected $fillable = ['hash', 'name', 'company', 'hiring_manager', 'content', 'properties'];

    protected $casts = [
        'properties' => 'json',
    ];
}
