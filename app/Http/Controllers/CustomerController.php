<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function show($id)
    {
        $data = [
            'customer' => null
        ];
        $phones = [];
        $emails = [];

        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response($data, 404);
        }

        $customer->first();

        foreach ($customer->customerPhones()->get() as $phone) {
            $phones[] = $phone->phone;
        }

        foreach ($customer->customerEmails()->get() as $email) {
            $emails[] = $email->email;
        }

        $data = [
            'customer' => [
                'last_name' => $customer->last_name,
                'first_name' => $customer->first_name,
                'phones' => $phones,
                'emails' => $emails,
            ]
        ];

        return response($data, 200);
    }
}
