<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        /** @var \Modules\Leguo\App\Models\Goods $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'stock_num' => $this->stock_num,
            'price' => $this->price,
            'currency' => $this->currency,
            'status' => $this->status,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
