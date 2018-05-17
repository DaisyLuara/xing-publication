<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ProjectAdLog;
use App\Transformers\ProjectAdLogTransformer;
use Illuminate\Http\Request;

class ProjectAdLogController extends Controller
{
    public function index(Request $request, ProjectAdLog $adLog)
    {
        $query = $adLog->query();

        if ($request->oid) {
            $query->where('ar_product_ad_log.oid', '=', $request->oid);
        }
        if ($request->wiid) {
            $query->where('ar_product_ad_log.wiid', '=', $request->wiid);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereRaw("date_format(ar_product_ad_log.date,'%Y-%m-%d') between '$request->start_date' and '$request->end_date'");
        }

        $adLog = $query->join('ar_product_list', 'ar_product_ad_log.piid', '=', 'ar_product_list.id')
            ->join('wx_third_info', 'ar_product_ad_log.wiid', '=', 'wx_third_info.id')
            ->join('avr_official', 'ar_product_ad_log.oid', '=', 'avr_official.oid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->join('avr_official_area', 'avr_official.areaid', '=', 'avr_official_area.areaid')
            ->groupBy('ar_product_ad_log.oid')
            ->orderBy('avr_official_area.areaid', 'desc')
            ->orderBy('avr_official_market.marketid', 'desc')
            ->orderBy('avr_official.oid', 'desc')
            ->groupBy('ar_product_ad_log.oid')
            ->selectRaw('count(*) as count,avr_official_area.name as areaName,avr_official_market.name as marketName,avr_official.name as pointName,avr_official.name as pointName,ar_product_list.name as projectName,ar_product_list.icon as projectIcon,wx_third_info.nick_name as advertiser,wx_third_info.head_img as ad_icon')
            ->paginate(10);

        return $this->response->paginator($adLog, new ProjectAdLogTransformer());
    }
}