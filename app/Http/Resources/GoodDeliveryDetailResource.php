<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodDeliveryDetailResource extends JsonResource
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
            'good_delivery_id' => $this->good_delivery_id,
            'product_name' => $this->product->name,
            'product_code' => $this->product->code,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'store_name' => $this->store->name,
            'store_id' => $this->store_id,
        ];
    }
}
