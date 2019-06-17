<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyExport extends BaseExport
{

    private $name;//公司名称
    private $internal_name;//公司简称
    private $category;//公司属性
    private $status;//状态
    private $bd_name;//所属BD


    public function __construct($request)
    {
        $this->name = $request->name;
        $this->internal_name = $request->internal_name;
        $this->category = $request->category;
        $this->status = $request->status;
        $this->bd_name = $request->bd_name;

        $this->fileName = '公司-公司管理列表';
    }

    public function collection()
    {

        /** @var  $currentUser \App\Models\User */
        $currentUser = Auth::user();

        $query = DB::table('companies as c')
            ->leftJoin('users as bd_user', 'c.bd_user_id', '=', 'bd_user.id')
            ->leftJoin('customers', 'c.id', '=', 'customers.company_id');


        if ($this->name) {
            $query->where('c.name', 'like', '%' . $this->name . '%');
        }

        if ($this->internal_name) {
            $query->where('c.internal_name', 'like', '%' . $this->internal_name . '%');
        }

        if ($this->category !== null) {
            $query->where('c.category', '=', $this->category);
        }

        if ($this->status !== null) {
            $query->where('c.status', '=', $this->status);
        }

        if ($this->bd_name) {
            $query->where('bd_user.name', 'like', '%' . $this->bd_name . '%');
        }

        //角色为管理员，法务，法务主管时，查看所有公司数据
        if (!$currentUser->isAdmin() && !$currentUser->hasRole('legal-affairs|legal-affairs-manager|operation')) {
            //角色为主管时，查看下属及自己
            if ($currentUser->parent_id === $currentUser->id) {
                $query->where('bd_user.parent_id', $currentUser->id);
            } else {
                $query->where('c.bd_user_id', $currentUser->id);
            }
        }

        $companies = $query->orderByDesc('c.id')
            ->selectRaw("c.id,c.name,c.address,c.internal_name, 
            case c.category when 1 then '供应商' else '客户' end as 'category' ,
            case c.status when 1 then '待合作' when 2 then '合作中' else '已结束' end as 'status',
            c.description,bd_user.name as 'db_name',c.created_at,c.updated_at,
            customers.id as '联系人ID',customers.name as 'customers_name',customers.position,concat('\t',customers.phone,'\t'),customers.telephone ")
            ->get()->toArray();

        $header = ['ID', '公司全称', '公司地址', '公司简称', '公司属性', '状态', '公司描述', '所属BD', '创建时间', '最后操作时间',
            '联系人ID', '联系人姓名', '联系人职务', '联系人手机号', '联系人座机'];

        $this->merge = collect($companies)->groupBy('id')->map(function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge_start = 1;
        $this->merge_end = 10;

        $this->header_num = count($header);
        array_unshift($companies, $header, $header);
        $this->data = $data = collect($companies);

        return $data;
    }


}