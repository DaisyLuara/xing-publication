<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Company\V1\Models\CompanyProject;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\Project
 *
 * @property int $id
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $tid 模板ID
 * @property int $uid 创建人
 * @property int $sid 场景id
 * @property string $name 名称
 * @property string $info 描述
 * @property string $icon 图标
 * @property string $image 介绍图
 * @property string|null $url 视频介绍
 * @property string $link 第三方链接
 * @property int $size 大小
 * @property string $packname 包名
 * @property string $versioncode 版本号,年月日时分
 * @property string $versionname 版本名称
 * @property float $top_num 人气指数
 * @property float $luck_num 运气指数
 * @property float $invite_num 推荐指数
 * @property int $credits 积分
 * @property int $rmb 金币
 * @property int $open 公开 0：否 1：是
 * @property int $scan 0: 不是 1：是
 * @property int $scanall 0: 不是 1：是
 * @property int $online 上线日期
 * @property int $res 分辨率
 * @property string $ori portrait：竖屏 landscape：横屏
 * @property string $date
 * @property int $clientdate 时间
 * @property int|null $policy_id 优惠券投放策略主键
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\CompanyProject $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Point[] $points
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereInviteNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereLuckNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereOri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project wherePackname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project wherePolicyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereRes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereRmb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereScan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereScanall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereTopNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereVersioncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\Project whereVersionname($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_list';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'tid',
        'name',
        'info',
        'icon',
        'image',
        'link',
        'size',
        'packname',
        'versioncode',
        'versionname',
        'open',
        'scan',
        'linkall',
        'date',
        'clientdate',
        'policy_id',
    ];

    public function points()
    {
        return $this->belongsToMany(Point::class, 'istar_tv_oid', 'default_plid', 'oid');
    }

    public function policy()
    {
        return $this->setConnection('mysql')->hasOne(Policy::class, 'id', 'policy_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProject::class, 'id', 'project_id');
    }

    public function permissions()
    {
        return $this->hasMany(ProjectPermission::class, 'pid', 'id');
    }

}
