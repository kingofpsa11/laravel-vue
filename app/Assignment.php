<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assignment extends Model
{
    protected $fillable = [
        'id',
        'contract_id',
        'factory_id',
        'number',
        'date',
        'status'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function assignmentDetails()
    {
        return $this->hasMany('App\AssignmentDetail');
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
