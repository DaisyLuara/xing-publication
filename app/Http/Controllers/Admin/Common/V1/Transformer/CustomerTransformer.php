<?php


namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    public function transform(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'position' => $customer->position,
            'phone' => $customer->phone,
            'telephone' => $customer->telephone,
            'company_name' => $customer->company ? $customer->company->name : '--',
        ];
    }
}