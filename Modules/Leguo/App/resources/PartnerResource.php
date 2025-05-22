<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        /** @var \Modules\Leguo\App\Models\Partner $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'desc' => $this->desc,
            'logo' => ImageResource::collection($this->whenLoaded('logo')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
