<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use League\Fractal\TransformerAbstract;

class MarketTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['area', 'contract', 'share', 'company', 'media', 'bdUser', 'adContract', 'marketConfig'];

    public function transform(Market $market)
    {
        return [
            'id' => (int)$market->marketid,
            'name' => (string)$market->name,
            'updated_at' => $market->marketConfig ? $market->marketConfig->updated_at->toDateTimeString() : null,
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

    public function includeMarketConfig(Market $market)
    {
        $marketConfig = $market->marketConfig;
        if ($marketConfig) {
            return $this->item($marketConfig, new MarketConfigTransformer());
        }
    }
}