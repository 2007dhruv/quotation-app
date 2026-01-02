<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_name',
        'address',
        'city',
        'state',
        'gst_no',
        'gst_type',
        'mobile',
        'email',
    ];
}
