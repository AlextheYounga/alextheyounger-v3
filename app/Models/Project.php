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
        'description',
        'image_name',
        'external_link',
        'external_image_link',
        'framework',
        'excerpt',
        'position',
        'properties',
        'active',
    ];

    protected $casts = [
        'properties' => 'json'
    ];

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
