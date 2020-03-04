<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }
}
