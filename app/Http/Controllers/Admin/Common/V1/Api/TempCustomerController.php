<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Models\TempCustomer;
use App\Http\Controllers\Admin\Common\V1\Request\TempCustomerRequest;
use App\Http\Controllers\Controller;


class TempCustomerController extends Controller
{

    public function all()
    {
        return TempCustomer::query()->count('id');
    }

    public function store(TempCustomer $customer, TempCustomerRequest $request)
    {
        $customer->fill($request->all())->saveOrFail();
        return $this->response->noContent();
    }

}
