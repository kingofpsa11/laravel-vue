<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MaterialRequisitionDetailResource;

class MaterialRequisitionResource extends JsonResource
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
            'date' => $this->date,
            'number' => $this->number,
            'department_name' => $this->department->name,
            'department_id' => $this->department_id,
            'status' => $this->status,
            'material_requisition_details' => MaterialRequisitionDetailResource::collection($this->materialRequisitionDetails),
        ];
    }
}
