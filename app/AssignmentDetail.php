<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AssignmentDetail extends Model
{
    protected $fillable = [
        'id',
        'assignment_id',
        'contract_detail_id',
        'product_id',
        'quantity',
        'deadline',
        'status'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10,
    ];

    public function assignment()
    {
        return $this->belongsTo('App\Assignment');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
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
}
