<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory;
use App\Models\Model;

class ThirdPartyUser extends Model
{

    public $table = 'third_party_users';
    public $primaryKey = 'id';

    public $fillable = [
        'subscribe',
        'nickname',
        'gender',
        'avatar',
        'age',
        'username',
        'openid',
        'mobile',
        'mallcoo_open_user_id',
        'birthday',
        'mallcoo_wx_open_id',
        'mall_card_apply_time',
    ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'subscribe' => 'boolean',
        'gendor' => 'boolean',
    ];

    public function mallcoo_score_histories()
    {
        return $this->hasMany(MallcooScoreHistory::class, 'mallcoo_open_user_id', 'mallcoo_open_user_id');
    }


}