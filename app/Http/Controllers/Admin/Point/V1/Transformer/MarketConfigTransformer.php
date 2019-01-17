<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\MarketConfig;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;

class MarketConfigTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['company', 'media', 'bdUser', 'adContract', 'writeOffCustomer'];

    public function transform(MarketConfig $marketConfig)
    {
        return [
            'marketid' => (int)$marketConfig->id,
            'phone' => (string)$marketConfig->phone,
            'address' => (string)$marketConfig->address,
            'description' => (string)$marketConfig->description,
        ];
    }

    public function includeCompany(MarketConfig $marketConfig)
    {
        $company = $marketConfig->company;
        if ($company) {
            return $this->item($company, new CompanyTransformer());
        }
    }

    public function includeMedia(MarketConfig $marketConfig)
    {
        $media = $marketConfig->media;
        if ($media) {
            return $this->item($media, new MediaTransformer());
        }
    }

    public function includeBdUser(MarketConfig $marketConfig)
    {
        $bdUser = $marketConfig->bdUser;
        if ($bdUser) {
            return $this->item($bdUser, new UserTransformer());
        }
    }

    public function includeAdContract(MarketConfig $marketConfig)
    {
        $adContract = $marketConfig->adContract;
        if ($adContract) {
            return $this->item($adContract, new ContractTransformer());
        }
    }

    public function includeWriteOffCustomer(MarketConfig $marketConfig)
    {
        $customer = $marketConfig->writeOffCustomer;
        if ($customer) {
            return $this->item($customer, new CustomerTransformer());
        }
    }

}