<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BomResource extends JsonResource
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
            'product_code' => $this->product->code,
            'product_name' => $this->product->name,
            'status' => $this->status,
            'bom_details' => BomDetailResource::collection($this->bomDetails)
        ];
    }
}
