<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:09
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward
 *
 * @property int $id
 * @property int $user_id
 * @property string $project_name
 * @property string $belong
 * @property string $type interaction:交互技术,originality:节目创意,h5:H5开发,animation:设计动画,plan:节目统筹,tester:节目测试,operation:平台运营,system:平台奖
 * @property string $main_type 绩效类别
 * @property string|null $total
 * @property string|null $date
 * @property string|null $get_date 发放日期
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereGetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereMainType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward whereUserId($value)
 * @mixin \Eloquent
 */
class TeamPersonReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'main_type',
        'total',
        'date',
        'type',
        'get_date'
    ];

    const MAIN_TYPE_CPE = 'CPE';
    const MAIN_TYPE_PBI = 'PBI';
    const MAIN_TYPE_SYSTEM = 'SYSTEM';

    public static $mainMapping = [
        self::MAIN_TYPE_CPE => 'CPE·节目智造奖金',
        self::MAIN_TYPE_PBI => 'P·B·I奖金',
        self::MAIN_TYPE_SYSTEM => '平台奖金',
    ];


    public static $typeMapping = [
        'plan' => '节目统筹',
        'originality' => '节目创意',
        'animation' => '设计动画',

        'interaction' => '交互技术',
        'h5' => 'H5开发',
        'backend_docking' => "后端IT技术支持",

        'tester' => '节目测试',
        'operation' => '平台运营',
        'tester_quality' => "节目测试-责任",
        'operation_quality' => "平台运营-责任",

        'animation_hidol' => '设计动画·Hidol',
        'hidol_patent' => 'Hidol专利',
    ];

    public static $otherTypeMapping = [
        'copyright' => '版权费',
    ];


    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeText()
    {
        $typeMapping = self::$typeMapping;
        $otherTypeMapping = self::$otherTypeMapping;

        $type_text_array = array_map(function ($item) use ($typeMapping, $otherTypeMapping) {
            return array_merge($typeMapping, $otherTypeMapping)[$item] ?? '*';
        }, explode('|', $this->type));

        $type_text = implode(';', $type_text_array);
        return $type_text;
    }


    public function getMainTypeText()
    {
        return (self::$mainMapping)[$this->main_type]??'';

    }

}
