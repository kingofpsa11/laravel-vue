<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ManufacturerOrder extends Model
{
    protected $fillable = ['id', 'contract_id', 'supplier_id', 'number', 'status', 'date'];

    public $timestamps = true;

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function manufacturerOrderDetails()
    {
        return $this->hasMany('App\ManufacturerOrderDetail');
    }

    protected $attributes = [
        'status' => 10,
    ];

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

    public static function getNewNumber($supplier_id)
    {
        return (self::whereYear('date', date('Y'))
            ->where('supplier_id', $supplier_id)
            ->max('number') + 1) ?? 1;
    }
}
