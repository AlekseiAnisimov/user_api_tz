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
}
