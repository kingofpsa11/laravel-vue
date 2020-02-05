<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerDetailResource extends JsonResource
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
            'product_code' => $this->contractDetail->price->product->code,
            'product_name' => $this->contractDetail->price->product->name,
            'quantity' => $this->contractDetail->quantity,
            'deadline' => $this->contractDetail->deadline,
            'note' => $this->contractDetail->note,
        ];
    }
}
