<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        return [
            'order_no' => $this->order_no,
            'payment_status' => $this->payment_status,
            'payment_time' => $this->payment_time,
            'amount' => $this->amount,
            'discount' => $this->discount,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
