<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LandingPageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'pages' => $this->pages,
            'drafts' => $this->drafts,
            'unpublished_changes' => $this->unpublished_changes,
        ];
    }
}
