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

class TeamPersonReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'experience_money',
        'xo_money',
        'link_money',
        'system_money',
        'total',
        'date',
        'type',
        'get_date'
    ];

    public static $typeMapping = [
        'interaction' => '交互技术',
        'originality' => '节目创意',
        'h5' => 'H5开发',
        'animation' => '设计动画',
        'plan' => '节目统筹',
        'tester' => '节目测试',
        'operation' => '平台运营',
        'system' => '平台奖',
        'animation_hidol' => '设计动画',
        'hidol_patent' => 'Hidol专利',
        'backend_docking' => "后端IT技术支持",
        'tester_quality' => "节目测试-责任",
        'operation_quality' => "平台运营-责任",

        'interaction|copyright' => '交互技术(原创)',
        'originality|copyright' => '节目创意(原创)',
        'h5|copyright' => 'H5开发(原创)',
        'animation|copyright' => '设计动画(原创)',
        'plan|copyright' => '节目统筹(原创)',
        'tester|copyright' => '节目测试(原创)',
        'operation|copyright' => '平台运营(原创)',
        'system|copyright' => '平台奖(原创)',
        'animation_hidol|copyright' => '设计动画(原创)',
        'hidol_patent|copyright' => 'Hidol专利(原创)',
        'backend_docking|copyright' => "后端IT技术支持(原创)",
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
