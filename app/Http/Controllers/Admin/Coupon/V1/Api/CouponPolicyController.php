<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Coupon\V1\Models\CouponPolicy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponPolicyTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponPolicyController extends Controller
{
    public function index(CouponPolicy $couponPolicy,Request $request)
    {
        $query=$couponPolicy->query();
        $couponPolicy=$query->paginate(10);
        return $this->response->paginator($couponPolicy,new CouponPolicyTransformer());
    }

    public function store(CouponPolicy $couponPolicy,Request $request)
    {
        $data=$request->all();
        $query=$couponPolicy->query();
        $query->create($data);
        return $this->response->noContent();
    }

    public function update(CouponPolicy $couponPolicy,Request $request)
    {
        $data=$request->all();
        $id=$data['id'];
        unset($data['id']);
        $query=$couponPolicy->query();
        $query->where('id',$id)->update($data);
        return $this->response->noContent();
    }
}