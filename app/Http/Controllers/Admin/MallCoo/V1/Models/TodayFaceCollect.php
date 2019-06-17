<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFaceCollect
 *
 * @property int $fcid
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $oid 门店ID
 * @property string $imei 星视度设备号
 * @property string $face_token face++的标识
 * @property int $fpid 人ID
 * @property string $type 类型 play：星视度 shop：店流统计
 * @property string $belong 归属
 * @property string $face 头像
 * @property string|null $feature 特征码
 * @property string $gender Female：女 Male：男
 * @property int $age 年龄
 * @property string $glass None：不佩戴 Dark：太阳镜 Normal：光学镜
 * @property float $pitch  X轴，抬头与低头效果
 * @property float $yaw Y轴，左右转头
 * @property float $roll Z轴
 * @property string $ua 设备号
 * @property int $isold 老用户 1：是
 * @property int $iscomeback 回头客 1：是
 * @property int $todaynum 今日刷脸次数
 * @property int $isshare 共享
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereFaceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereFcid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereFeature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereFpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereGlass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereIscomeback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereIsold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereIsshare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect wherePitch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereRoll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereTodaynum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TodayFaceCollect whereYaw($value)
 * @mixin \Eloquent
 */
class TodayFaceCollect extends Model
{
    public $connection = 'ar';

    protected $table = 'today_face_collect';

}
