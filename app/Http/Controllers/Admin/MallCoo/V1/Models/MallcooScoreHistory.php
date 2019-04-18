<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory
 *
 * @property int $id
 * @property string $mallcoo_open_user_id
 * @property float $score
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property-read \App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser $thirdPartyUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereMallcooOpenUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MallcooScoreHistory extends Model
{
    protected $table = 'mallcoo_score_histories';

    protected $fillable = [
        'score',
        'description',
    ];

    public function thirdPartyUser()
    {
        return $this->belongsTo(ThirdPartyUser::class, 'mallcoo_open_user_id', 'mallcoo_open_user_id');
    }

}
