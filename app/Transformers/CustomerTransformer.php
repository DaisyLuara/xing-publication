<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'status' => (int)$customer->status,
            'customer_name' => $customer->customer_name
        ];
    }

    public function includeUser(Customer $customer)
    {
        return $this->item($customer->user, new UserTransformer());
    }
}