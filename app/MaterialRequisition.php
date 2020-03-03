<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialRequisition extends Model
{
    protected $fillable = [
        'department_id',
        'number',
        'date',
        'store_id',
        'status'
    ];

    public $timestamp = true;

    protected $attributes = [
        'status' => 10
    ];

    public function materialRequisitionDetails()
    {
        return $this->hasMany('App\MaterialRequisitionDetail');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
