<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'external_link' => $this->external_link,
            'external_image_link' => $this->external_image_link,
            'scope' => $this->scope,
            'position' => $this->position,
            'content' => $this->content,
            'properties' => $this->properties,
            'active' => $this->active,
        ];
    }
}
