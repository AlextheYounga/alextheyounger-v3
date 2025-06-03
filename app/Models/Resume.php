<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Resume extends Model
{
    use HasFactory;
	use AsSource;

	protected static function boot()
	{
		parent::boot();

		static::saving(function ($model) {
			if (empty($model->hash)) {
				$len = 8;
				$randomHash = substr(hash('sha256', openssl_random_pseudo_bytes(22)),-$len);
				$model->hash = $randomHash;
			}
		});
	}

	protected $fillable = [
		'hash',
		'name',
		'bio',
		'contacts',
		'references',
		'experience',
		'education',
		'properties',
	];

	protected $casts = [
		'contacts' => 'array',
		'references' => 'array',
		'experience' => 'array',
		'education' => 'array',
		'properties' => 'array',
	];

	public function projects()
	{
		return $this->belongsToMany(Project::class, 'project_resume');
	}
}
