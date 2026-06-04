<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

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
        'active' => 'boolean',
        'properties' => 'json',
        'content' => 'json',
    ];

    public function resumes(): BelongsToMany
    {
        return $this->belongsToMany(Resume::class, 'project_resume');
    }

    public function reorderPositions(): self
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function resumeFormat(): array
    {
        return $this->only(['id', 'title', 'external_link', 'content']);
    }
}
