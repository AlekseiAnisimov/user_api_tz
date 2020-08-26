<?php


namespace App\Models;

use App\Models\{Customer, CustomerEmail, CustomerPhone};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

        $phones = CustomerPhone::where('phone', 'like', "%$phone%")->get();

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

    /**
     * @param integer|null $email
     * @return array|null
     */
    public static function searchByEmail(?string $email): ?array
    {
        $result = null;

        if (is_null($email)) {
            return $result;
        }

        $emails = CustomerEmail::where('email', 'like', "%$email%")->get();

        $emailsList = [];
        $customerPreview = null;
        foreach ($emails as $email) {
            $customer_id = (int)$email->customer_id;
            $emailsList[$customer_id]['emails'][] = $email->email;
            if ($customerPreview != $customer_id) {
                $customerPreview = $customer_id;
                $customer = $email->customer;

                $emailsList[$customer_id]['last_name'] = $customer->last_name;
                $emailsList[$customer_id]['first_name'] = $customer->first_name;

                foreach ($customer->customerPhones as $phone) {
                    $emailsList[$customer_id]['phones'][] = (int)$phone->phone;
                }
            }
        }



        $result  = [
            'customers' => $emailsList
        ];

        return $result;
    }

    public static function searchByAllParams($params): ?array
    {
        $lastName = $params['last_name'];
        $firstName = $params['first_name'];
        $phone = $params['phone'];
        $email = $params['email'];


        $query = DB::table('customer')
            ->join('customer_phone', 'customer.id', '=', 'customer_phone.customer_id')
            ->join('customer_email', 'customer.id', '=', 'customer_email.customer_id')
            ->select('customer.*', 'customer_phone.phone', 'customer_email.email');

        if (is_null($lastName)) {
            $query->where('customer.last_name', 'like', "%$lastName%");
        }

        if (is_null($firstName)) {
            $query->where('customer.first_name', 'like', "%$firstName%");
        }

        if (is_null($phone)) {
            $query->where('customer_phone.phone', 'like', "%$phone%");
        }

        if (is_null($email)) {
            $query->where('customer_email.email', 'like', "%$email%");
        }

        $customers = $query->get();

        foreach ($customers as $key => $customer) {
            $data[$customer->id]['last_name'] = $customer->last_name;
            $data[$customer->id]['first_name'] = $customer->first_name;
            $data[$customer->id]['phones'][] = (int)$customer->phone;
            $data[$customer->id]['emails'][] = $customer->email;
        }

        $result  = [
            'customers' => $data
        ];

        return $result;
    }
}
