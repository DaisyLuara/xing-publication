<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午5:39
 */

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Models\Model;

class RankAmountConfig extends Model
{
    protected $table = 'rank_amount_config';

    protected $fillable = [
        'rank',
        'amount'
    ];


}