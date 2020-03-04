<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionDetail extends Model
{
    protected $fillable = [
        'material_requisition_id',
        'product_id',
        'quantity',
        'note'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function material()
    {
        return $this->belongsTo('App\MaterialRequisition');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
