<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GoodDeliveryDetailResource;

class GoodDeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'customer_name' => $this->customer->name,
            'customer_id' => $this->customer->id,
            'number' => $this->number,
            'date' => $this->date,
            'good_delivery_details' => GoodDeliveryDetailResource::collection($this->goodDeliveryDetails),
        ];
    }
}
