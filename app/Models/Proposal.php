<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Proposal extends Model
{
    use HasFactory;
	use AsSource;

	protected static function boot()
	{
		parent::boot();

		static::saving(function ($model) {
			if (!empty($model->title) && empty($model->hash)) {
				$model->hash = hash('sha256', $model->title);
			}
		});
	}
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'client',
		'hash',
		'title',
		'content',
		'line_items',
		'total',
		'client_agreement',
		'client_signature',
		'client_sign_date',
		'completion_date',
		'properties',
    ];

	protected $casts = [
		'completion_date' => 'date',
		'content' => 'json',
        'line_items' => 'json',
        'properties' => 'json'
    ];
}
