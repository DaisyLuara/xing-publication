<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog;
use App\Http\Controllers\Admin\Coupon\V1\Request\CouponBatchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponBatchController extends Controller
{
    public function index(CouponBatch $couponBatch, Request $request)
    {
        $query = $couponBatch->query();
        $couponBatch = $query->paginate(10);
        return $this->response->paginator($couponBatch, new CouponBatchTransformer());
    }

    public function store(CouponBatchRequest $request, CouponBatch $couponBatch)
    {
        $user = $this->user();
        $data = $request->all();
        $data['create_user_id'] = $user->id;
        $query = $couponBatch->query();
        $couponBatch = $query->create($data);
        $date = Carbon::now()->toDateTimeString();
        CouponCountLog::create(['coupon_batch_id' => $couponBatch->id, 'date' => $date]);
        return $this->response->noContent();
    }

    public function update(CouponBatch $couponBatch, Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        unset($data['id']);
        $query = $couponBatch->query();
        $query->where('id', $id)->update($data);
    }
}