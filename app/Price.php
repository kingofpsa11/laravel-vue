<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Price extends Model
{
    protected $fillable = ['id', 'product_id', 'purchase_price', 'selling_price', 'status', 'effective_date'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contractDetails()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function setEffectiveDateAttribute($value)
    {
        $this->attributes['effective_date'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getEffectiveDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }
}
