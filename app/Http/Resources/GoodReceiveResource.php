<?php

namespace App\Http\Resources;

use App\GoodReceiveDetail;
use App\Http\Resources\GoodReceiveDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodReceiveResource extends JsonResource
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
            'supplier_name' => $this->supplier->name,
            'supplier_id' => $this->supplier_id,
            'date' => $this->date,
            'good_delivery_details' => GoodReceiveDetailResource::collection($this->goodReceiveDetails),
        ];
    }
}
