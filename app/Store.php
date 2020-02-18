<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['id', 'code', 'name', 'category', 'store_id', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 1
    ];

    public function childrenStore()
    {
        return $this->hasOne('App\Store', 'id', 'store_id');
    }
}
