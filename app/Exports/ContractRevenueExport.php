<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/6/3
 * Time: 下午4:27
 */

namespace App\Exports;

use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind;
use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class ContractRevenueExport extends AbstractExport
{
    private $startDate;
    private $endDate;

    private $kindMapping = [
        1 => '铺屏',
        2 => '销售',
        3 => '租赁',
        4 => '服务'
    ];

    public function __construct($request)
    {
        $this->fileName = '星视度营收';
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
    }

    public function collection()
    {
        $contract = DB::table('contracts')
            ->leftJoin('users', 'contracts.owner', '=', 'users.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('contract_receive_dates as crd', 'contracts.id', '=', 'crd.contract_id')
            ->leftJoin('invoice_receipts as ir', 'crd.invoice_receipt_id', '=', 'ir.id')
            ->whereRaw("contracts.created_at between '$this->startDate' and '$this->endDate' and contracts.status=3")
            ->groupBy(DB::Raw('users.name,contract_number'))
            ->selectRaw('contracts.id as id,users.name as username,contracts.contract_number as contract_number,filed_date,
                         contracts.amount as amount,sum(ir.receipt_money) as receipt_money ,companies.internal_name as internal_name,
                         contracts.kind as kind,contracts.common_num as common_num');

        $product = DB::table('contracts')
            ->leftJoin('contract_products as cp', 'contracts.id', '=', 'cp.contract_id')
            ->whereRaw("contracts.created_at between '$this->startDate' and '$this->endDate' and contracts.status=3")
            ->selectRaw('contracts.id as id,product_name,product_stock');

        $costKind = ContractCostKind::get();
        $max = '';
        foreach ($costKind as $item) {
            $max .= ",Max(case cck.alias when '$item->alias' then ccc.money else 0 end) $item->alias";
        }
        $cost = DB::table('contracts')
            ->leftJoin('contract_costs as cc', 'contracts.id', '=', 'cc.contract_id')
            ->leftJoin('contract_cost_contents as ccc', 'ccc.cost_id', '=', 'cc.id')
            ->leftJoin('contract_cost_kinds as cck', 'cck.id', '=', 'ccc.kind_id')
            ->whereRaw("contracts.created_at between '$this->startDate' and '$this->endDate' and contracts.status=3")
            ->groupBy('contracts.id')
            ->selectRaw("contracts.id as id $max");

        $revenue = DB::table(DB::raw("({$contract->toSql()}) a"))
            ->join(DB::raw("({$product->toSql()}) b"), 'a.id', '=', 'b.id')
            ->join(DB::raw("({$cost->toSql()}) c"), 'a.id', '=', 'c.id')
            ->orderBy('a.username')
            ->selectRaw('a.id as id,username,contract_number,filed_date,amount,receipt_money,internal_name,kind,product_name,product_stock,common_num,c.*')
            ->get();
        $header = ['负责人', '合同编号', '归档日期', '合同金额', '到账金额', '公司简称', '合同种类', '型号', '硬件数量', '定制节目数', '硬件费用', '物流费用', '运维费用', '4G网络费用', '人员差旅', '物料费用', '公司优惠', '其他'];
        $arr=[];
        foreach ($revenue as $item) {
            $arr[] = [
                'username' => $item->username,
                'contract_number' => $item->contract_number,
                'filed_date' => $item->filed_date,
                'amount' => $item->amount,
                'receipt_money' => $item->receipt_money,
                'internal_name' => $item->internal_name,
                'kind' => $this->kindMapping[$item->kind],
                'product_name' => $item->kind === 4 ? '软件' : $item->product_name,
                'product_stock' => $item->product_stock,
                'common_num' => $item->common_num,
                'hardware' => $item->hardware,
                'transport' => $item->transport,
                'operation' => $item->operation,
                'network' => $item->network,
                'travel' => $item->travel,
                'materiel' => $item->materiel,
                'discount' => $item->discount,
                'other' => $item->other
            ];
        }
        $this->merge1 = collect($arr)->groupBy('username')->map(function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge2 = collect($arr)->groupBy('contract_number')->map(function ($value) {
            return $value->count();
        })->values()->toArray();

        $this->headerNum = count($header);
        array_unshift($arr, $header, $header);
        $data = collect($arr);
        $this->data = $data;
        return $data;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = [];
                for ($i = 0; $i < $this->headerNum; $i++) {
                    $cellArray[] = $this->change($i) . '1:' . $this->change($i) . '2';
                }
                $start = 3;
                foreach ($this->merge1 as $key => $value) {
                    $cellArray[] = 'A' . $start . ':A' . ($start + $value - 1);
                    $start += $value;
                }

                $start = 3;
                foreach ($this->merge2 as $key => $value) {
                    for ($i = 1; $i <= 6; $i++) {
                        $cellArray[] = $this->change($i) . $start . ':' . $this->change($i) . ($start + $value - 1);
                    }
                    for ($i = 9; $i <= 17; $i++) {
                        $cellArray[] = $this->change($i) . $start . ':' . $this->change($i) . ($start + $value - 1);
                    }
                    $start += $value;
                }

                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }

    public function change($x): string
    {
        $map = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $t = '';
        while ($x >= 0) {
            $t = $map[$x % 26] . $t;
            $x = floor($x / 26) - 1;
        }
        return $t;
    }
}