<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;

class StoreTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['company', 'market', 'area', 'contract', 'media', 'user'];

    public function transform(Store $store)
    {
        return [
            'id' => (int)$store->marketid,
            'type' => (int)$store->type,
            'name' => $store->name,
            'logo' => $store->logo,
            'phone' => $store->phone,
            'address' => $store->address,
            'description' => $store->description,
        ];
    }

    public function includeArea(Store $store)
    {
        return $this->item($store->area, new AreaTransformer());
    }

    public function includeCompany(Store $store)
    {
        $company = $store->company;
        if ($company) {
            return $this->item($store->company, new CompanyTransformer());
        }
    }

    public function includeMarket(Store $store)
    {
        $market = $store->market;
        if ($market) {
            return $this->item($store->market, new MarketTransformer());
        }
    }

    public function includeContract(Store $store)
    {
        $contract = $store->contract;
        if ($contract) {
            return $this->item($store->contract, new ContractTransformer());
        }
    }

    public function includeMedia(Store $store)
    {
        if (!$store->media) {
            return null;
        }

        return $this->item($store->media, new MediaTransformer());
    }

    public function includeUser(Store $store)
    {
        if (!$store->user) {
            return null;
        }

        return $this->item($store->user, new UserTransformer());
    }


}