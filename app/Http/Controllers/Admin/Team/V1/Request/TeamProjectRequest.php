<?php

namespace App\Http\Controllers\Admin\Team\V1\Request;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamProjectRequest extends \App\Http\Requests\Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'belong' => 'required|unique:team_projects|exists:ar.ar_product_list,versionname',
                    'project_attribute' => Rule::in([1, 2, 3, 4]),
                    'link_attribute' => Rule::in([0, 1]),
                    'h5_attribute' => Rule::in([1, 2]),
                    'xo_attribute' => Rule::in([0, 1]),
                    'art_innovate' => 'required|max:1000',
                    'dynamic_innovate' => 'required|max:1000',
                    'interact_innovate' => 'required|max:1000',
                    'remark' => 'max:1000',
                    'type' => Rule::in([0, 1]),
                    'member.*.*.rate' => 'required|numeric',
                    'member.*.*.user_id' => 'required|integer|exists:users,id',
                    'media_id' => 'required|integer|exists:media,id'
                ];
                break;
            case 'PATCH':
                return [
                    'belong' => [
                        'required','exists:ar.ar_product_list,versionname',
                         Rule::unique('team_projects')->ignore($request->id),
                    ],
                    'project_attribute' => Rule::in([1, 2, 3, 4]),
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
                    'media_id' => 'required|integer|exists:media,id'
                ];
                break;
            default:
                return [];
        }

    }
}
