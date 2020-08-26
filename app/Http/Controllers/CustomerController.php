<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerPhone;
use App\Models\CustomerEmail;
use App\Models\CustomerSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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


    public function add(Request $request)
    {
       $body = $request->all();
       $valid = Validator::make($body, [
           'last_name' => 'required|string|min:2|max:255',
           'first_name' => 'required|string|min:2|max:255',
           'phones' => 'array',
           'phones.*' => 'integer',
           'emails' => 'array',
           'emails.*' => 'string|regex:/^.+@.+$/i'
       ]);

       if (sizeof($valid->errors()) > 0) {
           return response(['error' => ['message' => $valid->getMessageBag()], 'customer' => null], 400);
       }

       try {
           DB::transaction(function () use ($body) {
               $customer = new Customer();
               $customer->last_name = $body['last_name'];
               $customer->first_name = $body['first_name'];
               $customer->save();

               $customer_id = $customer->id;

               foreach ($body['phones'] as $phone) {
                   $customerPhone = new CustomerPhone();
                   $customerPhone->customer_id = $customer_id;
                   $customerPhone->phone = $phone;
                   $customerPhone->save();
               }

               foreach ($body['emails'] as $email) {
                   $customerEmail = new CustomerEmail();
                   $customerEmail->customer_id = $customer_id;
                   $customerEmail->email = $email;
                   $customerEmail->save();
               }

           });
       } catch (\Throwable $e) {
           DB::rollBack();
           return response(['error' => ['message' => $e->getMessage()], 'customer' => null], 400);
       }

        return response(['error' => ['message' => null], 'customer' => $body], 200);
    }

    public function delete($id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response(null, 404);
        }

        try {
            DB::transaction(function () use ($customer, $id) {
                $customer->delete();

                $customerPhone = CustomerPhone::where('customer_id', $id)->get();
                foreach ($customerPhone as $phone) {
                    $phone->delete();
                }

                $customerEmail = CustomerEmail::where('customer_id', $id)->get();
                foreach ($customerEmail as $email) {
                    $email->delete();
                }
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            return response(null, 400);
        }

        return response(null, 200);
    }

    public function search(Request $request)
    {
        //TODO:: добавить валидацию
        $searchType = $request->get('type');
        $body = $request->all();
        $result = null;

        switch ($searchType) {
            case 'fio':
                $result = CustomerSearch::searchByFio($body['last_name'], $body['first_name']);
                break;
            case 'phone':
                $result = CustomerSearch::searchByPhone($body['phone']);
                break;
            case 'email':
                $result = CustomerSearch::searchByEmail($body['email']);
                break;
            case 'all':
                $result = CustomerSearch::searchByAllParams($body);
                break;

        }

        if (is_null($result)) {
            return response([], 404);
        }

        return response($result, 200);
    }
}
