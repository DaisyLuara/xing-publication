<?php

namespace App\Http\Controllers\Admin\Contract\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ContractReceiveDateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'receive_date' => 'required'
            ];
        }
        return [];
    }
}

