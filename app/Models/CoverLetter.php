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
			if (!empty($model->name) && empty($model->hash)) {
				$model->hash = hash('crc32', $model->name);
			}
		});
	}

	protected $fillable = ['hash', 'name', 'company', 'hiring_manager', 'content', 'properties'];

	protected $casts = [
		'properties' => 'array',
	];
}
