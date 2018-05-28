<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['company'];

    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
        ];
    }

    public function includeCompany(Customer $customer)
    {
        return $this->item($customer->company, new CompanyTransformer());
    }
}