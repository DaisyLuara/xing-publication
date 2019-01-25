<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午7:49
 */


namespace App\Http\Controllers\Admin\Activity\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ActivityParticipantsRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'rank' => Rule::in([0, 1, 2, 3, 4]),
                    'auid' => 'required',
                ];
                break;

            default:
                return [];
        }

    }

}