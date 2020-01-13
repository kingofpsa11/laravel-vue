<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractDetail extends Model
{
    protected $fillable = ['id', 'contract_id', 'price_id', 'quantity', 'selling_price', 'supplier_id', 'deadline', 'note', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function outputOrderDetails()
    {
        return $this->hasMany('App\OutputOrderDetail');
    }

    public function manufacturerOrderDetail()
    {
        return $this->hasOne('App\ManufacturerOrderDetail');
    }

    public function manufacturerNoteDetails()
    {
        return $this->hasMany('App\ManufacturerNoteDetail');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function stepNoteDetails()
    {
        return $this->hasMany('App\StepNoteDetail');
    }

    public function shapeNoteDetails()
    {
        return $this->hasMany('App\ShapeNoteDetail');
    }

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDeadlineAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }

    //    public function setSellingPriceAttribute($value)
    //    {
    //        $this->attributes['selling_price'] = (int)str_replace('.', '', $value);
    //    }
    //
    //    public function getSellingPriceAttribute($value)
    //    {
    //        return number_format($value, 0, ',', '.');
    //    }
}
