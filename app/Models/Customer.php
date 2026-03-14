<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'address',
        'city',
        'state',
        'pin_code',
        'gst_no',
        'gst_type',
        'mobile',
        'email',
    ];
}
