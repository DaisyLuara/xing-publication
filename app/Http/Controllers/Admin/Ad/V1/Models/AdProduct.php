<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Ad\V1\Models\AdProduct
 *
 * @property int $adid
 * @property int $piid 节目ID
 * @property int $oid 点位信息
 * @property int $visiable 0：隐藏 1：显示
 * @property string $type 类型 normal:普通 mobile:手机号 publick:公众号 subk:订阅号 apps:小程序 tmall:天猫
 * @property int $wiid 第三方ID
 * @property int $wpid 会员通行证
 * @property string|null $wpinfo 通行证提示
 * @property string|null $wpurl 通行证链接
 * @property int $wpevery 0: 不是 1：是
 * @property string $url h5连接
 * @property string $title 标题
 * @property string $image 自定义封面
 * @property string $info 详情
 * @property string $reply 脱机码或回复内容
 * @property string|null $reply_url 脱机链接
 * @property string $e_title 错误标题
 * @property string $e_image 错误图片
 * @property string $e_info 错误信息
 * @property string $e_url 错误的链接
 * @property int $only 0：否 1：是
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $adTrade
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereAdid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereEImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereEInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereETitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereEUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct wherePiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereReplyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereVisiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereWiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereWpevery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereWpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereWpinfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdProduct whereWpurl($value)
 * @mixin \Eloquent
 */
class AdProduct extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_list';
    protected $primaryKey = 'adid';
    public $timestamps = false;

    public $fillable = [
        'adid',
        'url',
        'oid'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
