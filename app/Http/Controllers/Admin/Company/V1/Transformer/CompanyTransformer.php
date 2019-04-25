<?php

namespace App\Http\Controllers\Admin\Company\V1\Transformer;

use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'customers', 'bdUser', 'media', 'parent'];

    public function transform(Company $company)
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'internal_name' => $company->internal_name,
            'address' => $company->address,
            'category' => $company->category,
            'status' => (int)$company->status,
            'description' => $company->description,
            'logo' => $company->logo,
            'created_at' => $company->created_at->toDateTimeString(),
            'updated_at' => $company->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Company $company)
    {
        if ($company->user) {
            return $this->item($company->user, new UserTransformer());
        }
        return null;
    }

    public function includeCustomers(Company $company)
    {
        if ($company->customers->isNotEmpty()) {
            return $this->collection($company->customers, new CustomerTransformer());
        }
        return null;
    }

    public function includeBdUser(Company $company)
    {
        if ($company->bdUser) {
            return $this->item($company->bdUser, new UserTransformer());
        }
        return null;
    }

    public function includeMedia(Company $company)
    {
        $media = $company->media;
        if ($media) {
            return $this->item($company->media, new MediaTransformer());
        }
        return null;
    }

    public function includeParent(Company $company)
    {
        if ($company->parent) {
            return $this->item($company->parent, new CompanyTransformer());
        }
        return null;
    }


}