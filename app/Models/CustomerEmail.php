<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEmail extends Model
{
    protected $table = 'customer_email';

    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
