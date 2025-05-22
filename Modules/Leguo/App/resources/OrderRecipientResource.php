<?php

namespace Modules\Leguo\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        /** @var \Modules\Leguo\App\Models\OrderRecipient $this */
        return [
            'order_no' => $this->order_no,
            'suborder_no' => $this->suborder_no,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'receive_date' => $this->receive_date,
            'receive_time_span' => $this->receive_time_span,
            'id_no' => $this->id_no,
            'address' => $this->address,
            'remark' => $this->remark,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
