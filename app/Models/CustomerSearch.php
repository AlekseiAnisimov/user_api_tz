<?php


namespace App\Models;

use App\Models\{Customer, CustomerEmail, CustomerPhone};
use Illuminate\Database\Eloquent\Collection;

class CustomerSearch
{
    /**
     * @param string|null $lastName
     * @param string|null $firstName
     * @return Collection|null
     */
    public static function searchByFio(?string $lastName, ?string $firstName): ?Collection
    {

        $query = Customer::query();

        if (!is_null($lastName)) {
            $query->where('last_name', 'like',"%$lastName%");
        }

        if (!is_null($firstName)) {
            $query->where('first_name', 'like', "%$firstName%");
        }

        return $query->get(['id', 'last_name', 'first_name']);
    }

    /**
     * @param integer|null $phone
     * @return Collection|null
     */
    public static function searchByPhone(?int $phone): ?array
    {
        $result = null;

        if (is_null($phone)) {
            return $result;
        }

        $phones = CustomerPhone::where('phone', 'like', "$phone")->get();

        $phonesList = [];
        $customerPreview = null;
        foreach ($phones as $phone) {
            $customer_id = (int)$phone->customer_id;
            $phonesList[$customer_id]['phones'][] = (int)$phone->phone;
            if ($customerPreview != $customer_id) {
                $customerPreview = $customer_id;
                $customer = $phone->customer;

                $phonesList[$customer_id]['last_name'] = $customer->last_name;
                $phonesList[$customer_id]['first_name'] = $customer->first_name;
                foreach ($customer->customerEmails as $email) {
                    $phonesList[$customer_id]['emails'][] = $email->email;
                }
            }
        }



        $result  = [
            'customers' => $phonesList
        ];

        return $result;
    }
}
