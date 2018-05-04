<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles'];

    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'status' => $customer->status,
        ];
    }

    public function includeUser(Customer $customer)
    {
        return $this->collection($customer->user, new RoleTransformer());
    }
}