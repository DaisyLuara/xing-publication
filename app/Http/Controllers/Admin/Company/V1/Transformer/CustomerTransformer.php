<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Models\Customer;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['company', 'roles'];

    public function transform(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'position' => $customer->position,
            'phone' => $customer->phone,
            'telephone' => $customer->telephone,
            'z' => $customer->z,
            'created_at' => $customer->created_at->toDateTimeString(),
            'updated_at' => $customer->updated_at->toDateTImeString(),
        ];
    }

    public function includeRoles(Customer $customer)
    {
        if ($customer->roles->count() === 0) {
            return null;
        }
        return $this->collection($customer->roles, new RoleTransformer());
    }

    public function includeCompany(Customer $customer): Item
    {
        return $this->item($customer->company, new CompanyTransformer());
    }
}