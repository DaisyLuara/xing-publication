<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CouponExport extends AbstractExport implements ShouldAutoSize
{

    public function __construct($request)
    {
        $this->status = $request->has('status') ? $request->status : null;
        $this->couponBatchId = $request->coupon_batch_id;
        $this->companyId = $request->company_id;
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->shopCustomerId = $request->shop_customer_id;
        $this->fileName = '优惠券数据';
    }

    public function collection()
    {
        $query = DB::table('coupons')
            ->leftJoin('coupon_batches', 'coupons.coupon_batch_id', '=', 'coupon_batches.id')
            ->leftJoin('companies', 'coupon_batches.company_id', '=', 'companies.id')
            ->leftJoin('customers', 'coupons.shop_customer_id', '=', 'customers.id');

        if (!is_null($this->status)) {
            $query->where('coupons.status', '=', $this->status);
        }

        if (!is_null($this->couponBatchId)) {
            $query->where('coupons.coupon_batch_id', '=', $this->couponBatchId);
        }

        if (!is_null($this->companyId)) {
            $query->where('coupon_batches.company_id', '=', $this->companyId);
        }

        if (!is_null($this->startDate) && !is_null($this->endDate)) {
            $query->whereBetween('coupons.created_at', [$this->startDate, $this->endDate]);
        }

        if (!is_null($this->shopCustomerId)) {
            $query->where('coupons.shop_customer_id', '=', $this->shopCustomerId);
        }

        $loginUser = Auth::user();
        if ($loginUser->hasRole('user')) {
            $query->where('coupon_batches.bd_user_id', '=', $loginUser->id);
        }

        $query = $query->selectRaw("concat('\t',coupons.code,'\t') as 'code',coupon_batches.name as '名称',
                       case coupons.status
                       when 0 then '未领取'
                       when 1 then '已使用'
                       when 2 then '停用'
                       when 3 then '未使用'
                       end as '状态',coupons.mobile as '手机号',
                  coupons.wx_user_id as '微信ID',
                  coupons.taobao_user_id as '淘宝ID',
                  coupons.created_at as '创建时间',
                  coupons.use_date as '核销时间',
                  concat(ifnull(coupons.start_date,' '),'-',ifnull(coupons.end_date,' ')) as '有效日期',
                  companies.name as '公司',
                  coupons.oid as 'point',
                  customers.name as '核销人'");

        $points = DB::connection('ar')->table('avr_official')
            ->leftJoin('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->leftJoin('avr_official_area', 'avr_official_market.areaid', 'avr_official_area.areaid')
            ->selectRaw("concat(avr_official_area.name,'-',avr_official_market.name,'-',avr_official.name) as 'point_name',avr_official.oid as 'oid' ")
            ->pluck('point_name', 'oid')->toArray();

        $points = $query->orderBy('coupons.id', 'desc')->get()
            ->map(function ($value) use ($points) {
                if ($value->point > 0) {
                    $value->point = $points[$value->point]??'';
                } else {
                    $value->point = '';
                }
                return (array)$value;

            })->toArray();

        $header = ['编码', '名称', '状态', '手机号', '微信ID', '淘宝ID', '创建时间', '核销时间', '有效期', ' 公司', '点位', '核销人'];
        $this->headerNum = count($header);
        array_unshift($points, $header);
        $data = collect($points);
        $this->data = collect($data);

        return $data;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:L' . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                //编码被科学计数问题
                $event->sheet->getStyle('A1:A' . $this->data->count())->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:L' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:L1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                //冻结表头
                $event->sheet->getDelegate()->freezePane('A1');

            }
        ];
    }

}