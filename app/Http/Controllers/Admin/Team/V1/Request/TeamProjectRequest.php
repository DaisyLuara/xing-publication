<?php

namespace App\Http\Controllers\Admin\Team\V1\Request;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamProjectRequest extends \App\Http\Requests\Request
{
    public function attributes()
    {
        return [
            'belong' => "节目",
            'copyright_attribute' => "原创属性",
            'copyright_project_id' => "原创团队",
            'project_attribute' => "节目属性",
            'hidol_attribute' => "Hidol 属性",
            'individual_attribute' => "定制属性",
            'contract_id' => "合同",
            'interaction_attribute' => "交互属性",
            'link_attribute' => "联动属性",
            'h5_attribute' => "h5属性",
            'xo_attribute' => "小偶属性",
            'art_innovate' => "艺术风格创新点",
            'dynamic_innovate' => "动效体验创新点",
            'interact_innovate' => "交互技术创新点",
            'remark' => "备注",
            'type' => "节目类型",
            'member.*.*.rate' => "成员比例",
            'member.*.*.user_id' => "成员ID",
            'media_id' => "文件",
            'animation_media_id' => "动画素材文件",
            'tester_media_id' => "测试文件",
            'operation_media_id' => "运营文件",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'belong' => 'required|unique:team_projects|exists:ar.ar_product_list,versionname',
                    'copyright_attribute' => ['required', Rule::in([0, 1])],
                    'copyright_project_id' => ['nullable', 'required_if:copyright_attribute,1', 'integer',
                        Rule::exists('team_projects', 'id')->where(function ($query) {
                            $query->where('copyright_attribute', 0);
                        })],
                    'project_attribute' => ['required', Rule::in([0, 1, 2, 3, 4])],
                    'hidol_attribute' => ['required', Rule::in([0, 1])],
                    'individual_attribute' => ['required', Rule::in([0, 1, 2])],
                    'contract_id' => ['nullable', 'required_unless:individual_attribute,0', 'integer',
                        Rule::exists('contracts', 'id')->where(function ($query) {
                            $query->where('type', 0);
                        })
                    ],
                    'interaction_attribute' => "required|array",
                    'link_attribute' => ['required', Rule::in([0, 1])],
                    'h5_attribute' => ['required', Rule::in([1, 2])],
                    'xo_attribute' => [Rule::in([0, 1])],
                    'art_innovate' => 'required|max:1000',
                    'dynamic_innovate' => 'required|max:1000',
                    'interact_innovate' => 'required|max:1000',
                    'remark' => 'max:1000',
                    'type' => ['required', Rule::in([0, 1])],
                    'member.*.*.rate' => 'required|numeric',
                    'member.*.*.user_id' => 'required|integer|exists:users,id',
                    'media_id' => 'nullable|integer|exists:media,id',
                    'animation_media_id' => 'required|integer|exists:media,id',
                    'tester_media_id' => 'nullable|integer|exists:media,id',
                    'operation_media_id' => 'nullable|integer|exists:media,id',

                ];
                break;
            case 'PATCH':
                return [
                    'belong' => [
                        'required', 'exists:ar.ar_product_list,versionname',
                        Rule::unique('team_projects')->ignore($this->route("team_project")->id),
                    ],
                    'copyright_attribute' => ['required', Rule::in([0, 1])],
                    'copyright_project_id' => ['nullable', 'required_if:copyright_attribute,1', 'integer',
                        Rule::notIn([$this->route("team_project")->id]),
                        Rule::exists('team_projects', 'id')->where(function ($query) {
                            $query->where('copyright_attribute', 0);
                        })
                    ],
                    'project_attribute' => Rule::in([0, 1, 2, 3, 4]),
                    'hidol_attribute' => Rule::in([0, 1]),
                    'individual_attribute' => Rule::in([0, 1, 2]),
                    'contract_id' => ['nullable', 'required_unless:individual_attribute,0', 'integer',
                        Rule::exists('contracts', 'id')->where(function ($query) {
                            $query->where('type', 0);
                        })],
                    'interaction_attribute' => "required|array",
                    'link_attribute' => Rule::in([0, 1]),
                    'h5_attribute' => Rule::in([1, 2]),
                    'xo_attribute' => Rule::in([0, 1]),
                    'art_innovate' => 'max:1000',
                    'dynamic_innovate' => 'max:1000',
                    'interact_innovate' => 'max:1000',
                    'remark' => 'max:1000',
                    'type' => Rule::in([0, 1]),
                    'member.*.*.rate' => 'required|numeric',
                    'member.*.*.user_id' => 'required|integer|exists:users,id',
                    'media_id' => 'nullable|integer|exists:media,id',
                    'animation_media_id' => 'required|integer|exists:media,id',
                    'tester_media_id' => 'nullable|integer|exists:media,id',
                    'operation_media_id' => 'nullable|integer|exists:media,id',
                ];
                break;
            default:
                return [];
        }

    }
}
