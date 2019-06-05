<?php

namespace App\Http\Controllers\Admin\Demand\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DemandApplicationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'title' => 'required|string|unique:demand_applications',
                    'launch_point_remark' => 'required|string',
                    'has_contract' => ['required', 'integer', Rule::in([0, 1, 2])],
                    'contract_ids' => 'required_if:has_contract,1|nullable|array',
                    'project_num' => 'required|integer',
                    'similar_project_name' => 'nullable|string',
                    'expect_online_time' => 'required|date',
                    'expect_receiver_ids' => 'nullable|array',
                    'big_screen_demand' => 'required|string',
                    'small_screen_demand' => 'required|string',
                    'h5_demand' => 'required|string',
                    'other_demand' => 'required|string',
                    'applicant_remark' => 'nullable|string',
                ];
                break;

            case 'PATCH':
                return [
                    'title' => ['required', 'string',
                        Rule::unique('demand_applications')->ignore($this->route('demand_application')->id),
                    ],
                    'launch_point_remark' => 'required|string',
                    'has_contract' => ['required', 'integer', Rule::in([0, 1, 2])],
                    'contract_ids' => 'required_if:has_contract,1|nullable|array',
                    'project_num' => 'required|integer',
                    'similar_project_name' => 'nullable|string',
                    'expect_online_time' => 'required|date',
                    'expect_receiver_ids' => 'nullable|array',
                    'big_screen_demand' => 'required|string',
                    'small_screen_demand' => 'required|string',
                    'h5_demand' => 'required|string',
                    'other_demand' => 'required|string',
                    'applicant_remark' => 'nullable|string'
                ];
                break;

            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            'title' => '项目标的',
            'applicant_id' => '申请人',
            'owner' => '所属人',
            'launch_point_remark' => '投放地点备注',
            'has_contract' => '有无合同',
            'contract_ids' => '合同编号',
            'project_num' => '节目数量',
            'similar_project_name' => '类似节目列表',
            'expect_online_time' => '期望上线时间',
            'expect_receiver_ids' => '期望接单人',
            'big_screen_demand' => '大屏节目需求',
            'small_screen_demand' => '小屏定制内容',
            'h5_demand' => 'H5节目需求',
            'other_demand' => '其他定制内容',
            'applicant_remark' => '申请备注',
            'status' => '状态',
            'receiver_id' => '接单人ID',
            'receiver_name' => '接单人姓名',
            'receiver_remark' => '接单人备注',
            'receiver_time' => '接单时间',
            'confirm_id' => '确认完成人ID',
            'confirm_name' => '确认完成人姓名',
            'confirm_time' => '确认完成时间',
        ];

    }

}
