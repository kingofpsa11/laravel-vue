<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
    protected $fillable = [
        'id',
        'number',
        'customer_id',
        'total_value',
        'imprest',
        'status',
        'date',
        'user_id'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function contractDetails()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function manufacturerOrder()
    {
        return $this->hasMany('App\ManufacturerOrder');
    }

    //    public function setTotalValueAttribute($value)
    //    {
    //        $this->attributes['total_value'] = (int)str_replace('.', '', $value);
    //    }
    //
    //    public function getTotalValueAttribute($value)
    //    {
    //        return number_format($value, 0, ',', '.');
    //    }
}
