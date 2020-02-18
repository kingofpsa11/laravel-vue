<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoodDelivery extends Model
{
    protected $fillable = ['id', 'output_order_id', 'good_receive_id', 'number', 'date', 'customer_id', 'customer_user', 'status'];

    //    protected $dates = ['date'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10,
    ];

    public function deliverable()
    {
        return $this->morphTo();
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
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

    public static function getNewNumber()
    {
        return (self::whereYear('date', date('Y'))->max('number') + 1) ?? 1;
    }
}
