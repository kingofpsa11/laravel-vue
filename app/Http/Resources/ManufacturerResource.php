<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ManufacturerDetailResource;

class ManufacturerResource extends JsonResource
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
            'customer_name' => $this->manufacturerOrderDetails->first()->contractDetail->contract->customer->name,
            'manufacturer_order_number' => $this->number,
            'contract_number' => $this->manufacturerOrderDetails->first()->contractDetail->contract->number,
            'date' => $this->date,
            'status' => $this->status,
            'manufacturer_order_details' => ManufacturerDetailResource::collection($this->manufacturerOrderDetails),
        ];
    }
}
