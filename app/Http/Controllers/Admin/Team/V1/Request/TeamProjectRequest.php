<?php

namespace App\Http\Controllers\Admin\Team\V1\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
                    'project_name' => 'required',
                    'belong' => 'required|unique:team_projects',
                    'project_attribute' => Rule::in([1, 2, 3, 4]),
                    'link_attribute' => Rule::in([0, 1]),
                    'h5_attribute' => Rule::in([1, 2]),
                    'xo_attribute' => Rule::in([0, 1]),
                    'remark' => 'max:150',
                    'type' => Rule::in([0, 1]),
                    'member.*.rate'=>'numeric'
                ];
                break;
            case 'PATCH':
                return [
                    'belong' => Rule::unique('team_projects')->ignore($request->id),
                    'project_attribute' => Rule::in([1, 2, 3, 4]),
                    'link_attribute' => Rule::in([0, 1]),
                    'h5_attribute' => Rule::in([1, 2]),
                    'xo_attribute' => Rule::in([0, 1]),
                    'remark' => 'max:150',
                    'type' => Rule::in([0, 1]),
                    'member.*.rate'=>'numeric'
                ];
                break;
            default:
                return [];
        }

    }
}
