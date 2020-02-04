<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractDetailResource extends JsonResource
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
            'product_id' => $this->price->product->id,
            'product_code' => $this->price->product->code,
            'product_name' => $this->price->product->name,
            'quantity' => $this->quantity,
            'selling_price' => $this->selling_price,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier->name,
            'deadline' => $this->deadline,
            'note' => $this->note
        ];
    }
}
