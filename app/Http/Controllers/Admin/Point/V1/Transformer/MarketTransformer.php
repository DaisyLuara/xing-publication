<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;

class MarketTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['area', 'contract', 'share', 'company', 'media', 'bdUser', 'adContract', 'marketConfig'];

    public function transform(Market $market)
    {
        return [
            'id' => (int)$market->marketid,
            'name' => (string)$market->name,
        ];
    }

    public function includeArea(Market $market)
    {
        return $this->item($market->area, new AreaTransformer());
    }

    public function includeContract(Market $market)
    {
        $contract = $market->contract;
        if ($contract) {
            return $this->item($market->contract, new MarketContractTransformer());
        }
    }

    public function includeShare(Market $market)
    {
        $share = $market->share;
        if ($share) {
            return $this->item($market->share, new MarketShareTransformer());
        }
    }

//    public function includeCompany(Market $market)
//    {
//        $company = $market->marketConfig->company;
//        if ($company) {
//            return $this->item($company, new CompanyTransformer());
//        }
//    }
//
//    public function includeMedia(Market $market)
//    {
//        $media = $market->marketConfig->media;
//        if ($media) {
//            return $this->item($media, new MediaTransformer());
//        }
//    }
//
//    public function includeBdUser(Market $market)
//    {
//        $bdUser = $market->marketConfig->bdUser;
//        if ($bdUser) {
//            return $this->item($bdUser, new UserTransformer());
//        }
//    }
//
//    public function includeAdContract(Market $market)
//    {
//        $adContract = $market->marketConfig->adContract;
//        if ($adContract) {
//            return $this->item($adContract, new ContractTransformer());
//        }
//    }

    public function includeMarketConfig(Market $market)
    {
        $marketConfig = $market->marketConfig;
        if ($marketConfig) {
            return $this->item($marketConfig, new MarketConfigTransformer());
        }
    }
}