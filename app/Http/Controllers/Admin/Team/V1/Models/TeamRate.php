<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:41
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamRate
 *
 * @property int $id
 * @property string $interaction_api interaction:交互技术
 * @property string $originality 节目创意
 * @property string $h5_1 H5基础
 * @property string $h5_2 H5复杂
 * @property string $animation 设计动画
 * @property string $plan 节目统筹
 * @property string $tester 节目测试
 * @property string $operation 平台运营
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $backend_docking 后端IT技术对接
 * @property string $interaction_linkage 交互技术-联动引擎
 * @property string $tester_quality 节目测试-保质
 * @property string $operation_quality 节目运营-保质
 * @property string $animation_hidol 设计动画-hidol对接
 * @property string $hidol_patent hidol专利
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereAnimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereAnimationHidol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereBackendDocking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereH51($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereH52($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereHidolPatent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereInteractionApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereInteractionLinkage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereOperationQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereOriginality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereTesterQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TeamRate extends Model
{
    protected $fillable = [
        'originality',
        'plan',
        'animation',
        'animation_hidol',
        'hidol_patent',
        'interaction_api',
        'interaction_linkage',
        'backend_docking',
        'h5_1',
        'h5_2',
        'tester',
        'tester_quality',
        'operation',
        'operation_quality',
        'interaction',
    ];
}