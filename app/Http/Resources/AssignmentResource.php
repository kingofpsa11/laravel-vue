<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AssignmentDetailResource;

class AssignmentResource extends JsonResource
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
            'factory_name' => $this->factory->name ?? '',
            'factory_id' => $this->factory_id ?? '',
            'date' => $this->date,
            'status' => $this->status,
            'assignment_details' => AssignmentDetailResource::collection($this->assignmentDetails),
        ];
    }
}
