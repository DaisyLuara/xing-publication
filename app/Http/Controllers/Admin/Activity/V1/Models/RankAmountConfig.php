<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午5:39
 */

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Models\Model;

/**
 * Class RankAmountConfig
 *
 * @package App\Http\Controllers\Admin\Activity\V1\Models
 * @property float amount
 * @property int $id
 * @property int $rank
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RankAmountConfig extends Model
{
    protected $table = 'rank_amount_config';

    protected $fillable = [
        'rank',
        'amount'
    ];


}