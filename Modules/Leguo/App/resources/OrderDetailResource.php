<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        /** @var \Modules\Leguo\App\Models\OrderDetail $this */
        return [
            'order_no' => $this->order_no,
            'suborder_no' => $this->suborder_no,
            'goods_id' => $this->goods_id,
            'goods_name' => $this->goods_name,
            'goods_price' => $this->goods_price,
            'goods_currency' => $this->goods_currency,
            'goods_quantity' => $this->goods_quantity,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
