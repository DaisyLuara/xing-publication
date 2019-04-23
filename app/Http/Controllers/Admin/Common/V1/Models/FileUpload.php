<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Common\V1\Models\FileUpload
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereAcid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereAvstate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereClientfid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereFpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereParms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereQrcodedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereRunbgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereShareNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\FileUpload whereUrl($value)
 * @mixin \Eloquent
 */
class FileUpload extends Model
{
    protected $connection = 'ar';
    public $table = 'file_upload';
}
