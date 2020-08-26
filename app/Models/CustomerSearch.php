<?php


namespace App\Models;

use App\Models\{Customer, CustomerEmail, CustomerPhone};
use Illuminate\Database\Eloquent\Collection;

class CustomerSearch
{
    /**
     * @param string|null $lastName
     * @param string|null $firstName
     * @return array|null
     */
    public static function searchByFio(?string $lastName, ?string $firstName): ?array
    {

        $query = Customer::query();

        if (!is_null($lastName)) {
            $query->where('last_name', 'like',"%$lastName%");
        }

        if (!is_null($firstName)) {
            $query->where('first_name', 'like', "%$firstName%");
        }

        $customers = $query->get();
        $data = [];
        foreach ($customers as $customer) {
            $id = (int)$customer->id;
            $data[$id]['last_name'] = $customer->last_name;
            $data[$id]['first_name'] = $customer->first_name;
            foreach ($customer->customerPhones as $phones) {
                $data[$id]['phone'][] = (int)$phones->phone;
            }
            foreach ($customer->customerEmails as $emails) {
                $data[$id]['emails'][] = $emails->email;
            }
        }

        $result = [
            'customers' => $data
        ];

        return $result;
    }

    /**
     * @param integer|null $phone
     * @return array|null
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
