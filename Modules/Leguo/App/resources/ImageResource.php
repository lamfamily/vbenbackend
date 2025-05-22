<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        /** @var \Modules\Leguo\App\Models\Image $this */
        return [
            'id' => $this->id,
            'url' => $this->url,
            'type' => $this->pivot->type ?? null,
            'sort_order' => $this->pivot->sort_order ?? null,
        ];
    }
}
