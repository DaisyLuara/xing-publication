<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['company'];

    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'position' => $customer->position,
            'phone' => $customer->phone,
            'telephone' => $customer->telephone,
            'created_at' => $customer->created_at->toDateTimeString(),
            'updated_at'=>$customer->updated_at->toDateTImeString()
        ];
    }

    public function includeCompany(Customer $customer)
    {
        return $this->item($customer->company, new CompanyTransformer());
    }
}