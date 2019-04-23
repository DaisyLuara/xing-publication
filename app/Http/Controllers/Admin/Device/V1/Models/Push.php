<?php

namespace App\Http\Controllers\Admin\Device\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Device\V1\Models\Push
 *
 * @property int $id
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $oid 门店ID
 * @property string|null $thours  定时重启
 * @property int $hdmi 0：关闭 1：开启
 * @property int $hdmidiv 自定义分辨率 0: 不是 1：是
 * @property string $name 设备名
 * @property string $ua 设备型号
 * @property string $systemversion 系统版本
 * @property string $curversion 当前版本
 * @property string $updateversion 更新版本
 * @property int $updatedate 最后的更新时间
 * @property string $imei 设备号
 * @property string $mcode 机器编号
 * @property string $system ios或android
 * @property string|null $token 用户标示
 * @property string|null $alias 区域，产品名
 * @property string|null $aliastype 作用域，包名
 * @property string $display 系统号
 * @property int $efacetime 互动报错时间
 * @property int $facedate 互动时间
 * @property int $networkdate 网络时间
 * @property int $resetdate 重启命令
 * @property int $screenshotdate 截图命令
 * @property string $did 智控标识
 * @property int $wpid 智控id
 * @property int $state 状态
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereAliastype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereCurversion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereDid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereEfacetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereFacedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereHdmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereHdmidiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereMcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereNetworkdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereResetdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereScreenshotdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereSystemversion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereThours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereUpdatedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereUpdateversion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\Push whereWpid($value)
 * @mixin \Eloquent
 */
class Push extends Model
{
    protected $connection = 'ar';
    public $table = 'push';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'alias', 'versionname');
    }
}
