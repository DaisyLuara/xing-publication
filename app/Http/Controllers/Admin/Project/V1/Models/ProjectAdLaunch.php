<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch
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
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @property-read \App\Http\Controllers\Admin\WeChat\V1\Models\WxThird $wxThird
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereAdid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereEImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereEInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereETitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereEUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch wherePiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereReplyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereVisiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereWiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereWpevery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereWpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereWpinfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch whereWpurl($value)
 * @mixin \Eloquent
 */
class ProjectAdLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_list';
    protected $primaryKey = 'adid';
    public $timestamps = false;

    public $fillable = [
        'piid',
        'oid',
        'visiable',
        'type',
        'wiid',
        'url',
        'title',
        'image',
        'info',
        'reply',
        'e_title',
        'e_image',
        'e_info',
        'e_url',
        'only',
        'date',
        'clientdate'
    ];

    public function wxThird()
    {
        return $this->belongsTo(WxThird::class, 'wiid', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }
}
