<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\StoreTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;

class CouponBatchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'company', 'wechat', 'market', 'point', 'writeOffMarket', 'writeOffStore'];

    public function transform(CouponBatch $couponBatch)
    {
        return [
            'id' => $couponBatch->id,
            'name' => $couponBatch->name,
            'description' => $couponBatch->description,
            'image_url' => preg_replace('/exe666.com/','xingstation.cn', $couponBatch->image_url),
            'bs_image_url' => preg_replace('/exe666.com/','xingstation.cn', $couponBatch->bs_image_url),
            'amount' => $couponBatch->amount,
            'count' => $couponBatch->count,
            'stock' => $couponBatch->stock,
            'people_max_get' => $couponBatch->people_max_get,
            'pmg_status' => $couponBatch->pmg_status,
            'day_max_get' => $couponBatch->day_max_get,
            'dmg_status' => $couponBatch->dmg_status,
            'is_fixed_date' => $couponBatch->is_fixed_date,
            'delay_effective_day' => $couponBatch->delay_effective_day,
            'effective_day' => $couponBatch->effective_day,
            'start_date' => $couponBatch->start_date,
            'end_date' => $couponBatch->end_date,
            'is_active' => $couponBatch->is_active,
            'third_code' => $couponBatch->third_code,
            'pivot' => $couponBatch->pivot,
            'wx_user_id' => $couponBatch->wx_user_id,
            'type' => (int)$couponBatch->type,
            'redirect_url' => $couponBatch->redirect_url,
            'title' => $couponBatch->title,
            'sort_order' => $couponBatch->sort_order,
            'dynamic_stock_status' => $couponBatch->dynamic_stock_status,
            'write_off_status' => $couponBatch->write_off_status,
            'credit' => $couponBatch->credit,
            'scene_type' => $couponBatch->scene_type,
            'updated_at' => $couponBatch->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(CouponBatch $couponBatch)
    {
        return $this->item($couponBatch->user, new UserTransformer());
    }

    public function includeCompany(CouponBatch $couponBatch)
    {
        if (!$couponBatch->company) {
            return null;
        }
        return $this->item($couponBatch->company, new CompanyTransformer());
    }

    public function includeWechat(CouponBatch $couponBatch)
    {
        return $this->item($couponBatch->wechat, new WecahtCouponBatchTransformer());
    }

    public function includeMarket(CouponBatch $couponBatch)
    {
        if (!$couponBatch->marketPointCouponBatches->first()) {
            return null;
        }

        return $this->item($couponBatch->marketPointCouponBatches->first()->market, new MarketTransformer());
    }

    public function includePoint(CouponBatch $couponBatch)
    {
        $points = collect();
        $couponBatch->marketPointCouponBatches->each(function ($item) use($points){
            if ($item->point) {
                $points->push($item->point);
            }
        });

        if ($points->isEmpty()) {
            return null;
        }

        return $this->collection($points, new PointTransformer());
    }

    public function includeWriteOffMarket(CouponBatch $couponBatch)
    {
        $market = $couponBatch->writeOffMarket;
        if ($market) {
            return $this->item($market, new MarketTransformer());
        }
    }

    public function includeWriteOffStore(CouponBatch $couponBatch)
    {
        if (empty($couponBatch->write_off_sid)) {
            return null;
        }

        $stores = collect();
        foreach ($couponBatch->write_off_sid as $store_id) {
            $stores->push(Store::find($store_id));
        }

        return $this->collection($stores, new StoreTransformer());
    }
}