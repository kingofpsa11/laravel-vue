<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContractDetailCollection;
use App\Http\Resources\ContractDetailResource;

class ContractResource extends JsonResource
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
            'number' => $this->number,
            'customer_name' => $this->customer->name,
            'customer_id' => $this->customer_id,
            'date' => $this->date,
            'contract_details' => ContractDetailResource::collection($this->contractDetails),
            'total_value' => $this->total_value
        ];
    }
}
