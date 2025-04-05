<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Project extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image_name',
        'external_link',
        'external_image_link',
        'techstack',
        'excerpt',
        'position',
        'properties',
        'scope',
        'active',
    ];

    protected $casts = [
        'properties' => 'json',
        'techstack' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

	public function resumes()
	{
		return $this->belongsToMany(Resume::class, 'project_resume');
	}

    public function reorderPositions()
    {
        $oldPosition = $this->getOriginal('position');
        $newPosition = $this->position;
        if ($oldPosition === $newPosition) {
            return $this;
        }
        
        $projects = Project::where('position', '>=', $newPosition)->orderBy('position')->get();

        foreach ($projects as $project) {
            if ($this->id == $project->id) {
                continue;
            }

            $newPosition++;
            $project->position = $newPosition;
            $project->save();
        }

        return $this;
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }
}
