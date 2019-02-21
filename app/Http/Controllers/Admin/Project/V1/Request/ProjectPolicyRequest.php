<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Project\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProjectPolicyRequest extends Request
{

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
                    'policy_id' => 'required|exists:policies,id',
                    'oids' => 'required|array|max:10',
                ];
                break;
            case 'PATCH':
                return [
                    'policy_id' => 'required|exists:policies,id',
                    'oids' => 'required|array|max:10',
                ];
                break;
        }
    }
}
