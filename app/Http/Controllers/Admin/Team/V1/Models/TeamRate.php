<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:41
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

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