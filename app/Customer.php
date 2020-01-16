<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['id', 'code', 'name', 'short_name', 'address', 'tax_registration_number'];

    public $timestamps = true;

    public function contracts()
    {
        return $this->hasMany('App\Contract');
    }

    public function outputOrders()
    {
        return $this->hasMany('App\OutputOrder');
    }
}
