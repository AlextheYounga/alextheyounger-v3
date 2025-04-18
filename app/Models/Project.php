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
		'title',
		'external_link',
		'external_image_link',
		'scope',
		'position',
		'content',
		'properties',
		'active',
    ];

    protected $casts = [
        'properties' => 'json',
        'content' => 'json',
    ];

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

	public function resumeFormat() {
		return $this->only(['id', 'title', 'link', 'content']);
	}
}
