<?php

namespace App\Http\Controllers\Admin\Team\V1\Request;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [

                ];
                break;
            case 'PATCH':
                return [
                ];
                break;
            default:
                return [];
        }

    }
}
