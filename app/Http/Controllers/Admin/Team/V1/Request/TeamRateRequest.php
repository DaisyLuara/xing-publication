<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:52
 */

namespace App\Http\Controllers\Admin\Team\V1\Request;


use App\Http\Requests\Request;

class TeamRateRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    "originality" => "required|numeric",
                    "plan" => "required|numeric",
                    "animation" => "required|numeric",
                    "animation_hidol" => "required|numeric",
                    "hidol_patent" => "required|numeric",
                    "interaction_api" => "required|numeric",
                    "interaction_linkage" => "required|numeric",
                    "backend_docking" => "required|numeric",
                    "h5_1" => "required|numeric",
                    "h5_2" => "required|numeric",
                    "tester" => "required|numeric",
                    "tester_quality" => "required|numeric",
                    "operation" => "required|numeric",
                    "operation_quality" => "required|numeric"
                ];
                break;
            case 'PATCH':
                return [
                    "originality" => "required|numeric",
                    "plan" => "required|numeric",
                    "animation" => "required|numeric",
                    "animation_hidol" => "required|numeric",
                    "hidol_patent" => "required|numeric",
                    "interaction_api" => "required|numeric",
                    "interaction_linkage" => "required|numeric",
                    "backend_docking" => "required|numeric",
                    "h5_1" => "required|numeric",
                    "h5_2" => "required|numeric",
                    "tester" => "required|numeric",
                    "tester_quality" => "required|numeric",
                    "operation" => "required|numeric",
                    "operation_quality" => "required|numeric"
                ];
                break;
            default:
                return [];
        }

    }
}