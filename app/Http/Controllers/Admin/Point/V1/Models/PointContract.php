<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


class PointContract extends ArModel
{
    public $table = 'avr_official_contract';
    protected $primaryKey = 'oid';

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

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
