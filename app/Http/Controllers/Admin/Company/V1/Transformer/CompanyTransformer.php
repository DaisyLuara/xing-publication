<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'customers'];

    public function transform(Company $company)
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'address' => $company->address,
            'status' => (int)$company->status,
            'created_at' => $company->created_at->toDateTimeString(),
            'updated_at' => $company->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Company $company)
    {
        $user = $company->user;
        if ($user) {
            return $this->item($company->user, new UserTransformer());
        }
    }

    public function includeCustomers(Company $company)
    {
        return $this->collection($company->customers, new CustomerTransformer());
    }

}