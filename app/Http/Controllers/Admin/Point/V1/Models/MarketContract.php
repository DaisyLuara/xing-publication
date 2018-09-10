<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


class MarketContract extends ArModel
{
    public $table = 'avr_official_market_contract';
    protected $primaryKey = 'marketid';
    protected $fillable = [
        'marketid', 'type', 'contract', 'contract_company', 'contract_num', 'contract_user', 'contract_phone', 'mode', 'ad_istar',
        'enter_sdate', 'enter_edate', 'oper_sdate', 'oper_edate',
        'ad_ads', 'exchange_num', 'date', 'clientdate'
    ];

    public function setEnterSdateAttribute($value)
    {
        $this->attributes['enter_sdate'] = strtotime($value);
    }

    public function setEnterEdateAttribute($value)
    {
        $this->attributes['enter_edate'] = strtotime($value);
    }

    public function setOperSdateAttribute($value)
    {
        $this->attributes['oper_sdate'] = strtotime($value);
    }

    public function setOperEdateAttribute($value)
    {
        $this->attributes['oper_edate'] = strtotime($value);
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
