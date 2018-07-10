<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Request\CouponBatchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponBatchController extends Controller
{
    public function show(CouponBatch $couponBatch)
    {
        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }

    public function index(CouponBatch $couponBatch, Request $request)
    {
//        $mall_coo = app('mall_coo');
//        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/PutIn/v1/GetAll/';
//        $result = $mall_coo->send($sUrl);
//        if ($result['Code'] == 1) {
//            return $result['Data'];
//        }

//        return $result['Message'];

        $query = $couponBatch->query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $couponBatch = $query->paginate(10);
        return $this->response->paginator($couponBatch, new CouponBatchTransformer());
    }

    public function store(Company $company, CouponBatch $couponBatch, CouponBatchRequest $request)
    {
        $couponBatch->fill(array_merge([
            'company_id' => $company->id,
            'create_user_id' => $this->user->id,
        ], $request->all()))->save();

        return $this->response->item($couponBatch, new CouponBatchTransformer())
            ->setStatusCode(201);
    }

    public function update(CouponBatch $couponBatch, Request $request)
    {
        $couponBatch->update($request->all());
        return $this->response->item($couponBatch, new CouponBatchTransformer())
            ->setStatusCode(201);
    }
}