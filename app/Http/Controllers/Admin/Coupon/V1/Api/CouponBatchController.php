<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponBatchController extends Controller
{
    public function index(CouponBatch $couponBatch,Request $request)
    {
        $query=$couponBatch->query();
        $couponBatch=$query->paginate(10);
        $this->response->paginator($couponBatch,new CouponBatchTransformer());
    }

    public function store(CouponBatch $couponBatch,Request $request)
    {
        $data=$request->all();
        $query=$couponBatch->query();
        $query->create($data);
        return $this->response->noContent();
    }

    public function update(CouponBatch $couponBatch,Request $request)
    {
        $data=$request->all();
        $id=$data['id'];
        unset($data['id']);
        $query=$couponBatch->query();
        $query->where('id',$id)->update($data);
    }
}