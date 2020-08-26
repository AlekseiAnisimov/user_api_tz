<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerPhone;
use App\Models\CustomerEmail;

class Customer extends Model
{
    protected $table = 'customer';

    public function customerPhones()
    {
        return $this->hasMany(CustomerPhone::class);
    }

    public function customerEmails()
    {
        return $this->hasMany(CustomerEmail::class);
    }
}
