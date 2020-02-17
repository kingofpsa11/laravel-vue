<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentDetailResource extends JsonResource
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
            'assignment_id' => $this->assignment_id,
            'contract_detail_id' => $this->contract_detail_id,
            'manufacturer_order_number' => $this->contractDetail->manufacturerOrderDetail->manufacturerOrder->number,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'product_code' => $this->product->code,
            'quantity' => $this->quantity,
            'deadline' => $this->deadline,
            'status' => $this->status,
        ];
    }
}
