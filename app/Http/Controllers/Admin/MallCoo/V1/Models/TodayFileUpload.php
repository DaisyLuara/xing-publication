<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFileUpload
 *
 * @property int $id
 * @property int $uid 用户uid
 * @property string $title 标题
 * @property string $image 背景图
 * @property string $code 合成图片
 * @property string|null $parms 自定义参数
 * @property string $url 地址
 * @property int $size 文件大小
 * @property int $speed 上传速率b
 * @property string $type video：视频 image：图片 other：其他
 * @property string|null $imei 设备号
 * @property int $qrcode 二维码生成
 * @property int $qrcodedate 二维码生成时间
 * @property int $share 1：被分享
 * @property int $share_num 扫描次数
 * @property string $belong 归属
 * @property int $bid 皮肤ID
 * @property int $oid 门店ID
 * @property int $acid 活动ID
 * @property int $avstate 0:关闭 1:开启
 * @property int $clientfid 客户端人脸ID
 * @property int $fpid 人ID
 * @property int $runbgt 后台线程收集
 * @property string|null $mobile 手机号码
 * @property string|null $unionid 授权广告用户ID
 * @property string|null $openid 第三方授权KEY
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereAcid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereAvstate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereClientfid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereFpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereParms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereQrcodedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereRunbgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereShareNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFileUpload whereUrl($value)
 * @mixin \Eloquent
 */
class TodayFileUpload extends Model
{
    public $connection = 'ar';

    protected $table = 'today_file_upload';

}
