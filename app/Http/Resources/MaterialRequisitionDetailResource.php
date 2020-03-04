<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialRequisitionDetailResource extends JsonResource
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
            'product_name' => $this->product->name,
            'product_code' => $this->product->code,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
