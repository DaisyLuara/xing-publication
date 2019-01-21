<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['company', 'roles'];

    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'position' => $customer->position,
            'phone' => $customer->phone,
            'telephone' => $customer->telephone,
            'created_at' => $customer->created_at->toDateTimeString(),
            'updated_at' => $customer->updated_at->toDateTImeString(),
        ];
    }

    public function includeRoles(Customer $customer)
    {
        return $this->collection($customer->role, new RoleTransformer());
    }

    public function includeCompany(Customer $customer)
    {
        return $this->item($customer->company, new CompanyTransformer());
    }
}