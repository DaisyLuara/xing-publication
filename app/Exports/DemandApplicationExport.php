<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DemandApplicationExport extends BaseExport
{
    private $title;//项目标的
    private $applicant_id;//申请人ID
    private $owner;//所属人ID
    private $status;//状态
    private $receiver_id;//接单人ID
    private $create_start_date, $create_end_date; //创建开始日期,结束日期


    public function __construct($request)
    {
        $this->create_start_date = $request->create_start_date;
        $this->create_end_date = $request->create_end_date;
        $this->title = $request->title;
        $this->status = $request->status;
        $this->applicant_id = $request->applicant_id;
        $this->owner = $request->owner;
        $this->receiver_id = $request->receiver_id;

        $this->fileName = '需求-申请管理列表';
    }

    public function collection()
    {

        $query = DemandApplication::query();
        /** @var User $user */
        $user = Auth::user();
        if ($this->title) {
            $query->where('title', 'like', "%" . $this->title . "%");
        }

        if ($this->applicant_id) {
            $query->where('applicant_id', '=', $this->applicant_id);
        }

        if ($this->owner) {
            $query->where('owner', '=', $this->owner);
        }

        if ($this->status !== null) {
            $query->where('status', '=', $this->status ?? 0);
        }

        if ($this->receiver_id) {
            $query->where('receiver_id', '=', $this->receiver_id);
        }

        if ($this->create_start_date && $this->create_end_date) {
            $query->whereRaw("date_format(created_at, '%Y-%m-%d') between '$this->create_start_date' and '$this->create_end_date'");
        }

        if ($user->hasRole('bd-manager')) {
            //BD主管可查看自己及下属BD新建的申请列表
            $user_ids = $user->subordinates()->pluck('id')->toArray();
            $user_ids[] = $user->id;
            $query->whereIn('owner', $user_ids);
        } else if ($user->hasRole('user') || $user->hasRole('business-operation')) {
            //只能查询自己创建的 Application
            $query->where('owner', '=', $user->id);
        }


        $users = User::query()->pluck('name', 'id')->toArray();

        $demandApplications = $query->orderBy('id', 'desc')->get()
            ->map(static function (DemandApplication $demandApplication) use ($users) {

                $expect_receiver_names = array_map(
                    static function ($user_id) use ($users) {
                        return $users[$user_id] ?? $user_id;
                    },
                    explode(',', $demandApplication->getExpectReceiverIds()));

                return [
                    'id' => $demandApplication->getId(),
                    'title' => $demandApplication->getTitle(),
                    'applicant_name' => $demandApplication->applicant->name,
                    'owner_name' => $demandApplication->owner_user->name,
                    'created_at' => (string)$demandApplication->getCreatedAt(),
                    'has_contract' => $demandApplication->getHasContractText(),
                    'contract_no_string' => "\t" . implode(',', $demandApplication->contracts()->pluck('contract_number')->toArray()) . "\t",
                    'project_num' => $demandApplication->getProjectNum(),
                    'similar_project_name' => $demandApplication->getSimilarProjectName(),
                    'expect_online_time' => Carbon::parse($demandApplication->getExpectOnlineTime())->toDateString(),
                    'expect_receiver_names' => implode(',', $expect_receiver_names),
                    'launch_point_remark' => $demandApplication->getLaunchPointRemark(),
                    'big_screen_demand' => $demandApplication->getBigScreenDemand(),
                    'small_screen_demand' => $demandApplication->getSmallScreenDemand(),
                    'h5_demand' => $demandApplication->getH5Demand(),
                    'other_demand' => $demandApplication->getOtherDemand(),
                    'receiver_name' => $demandApplication->getReceiverName(),
                    'receiver_remark' => $demandApplication->getReceiverRemark(),
                    'receiver_time' => (string)$demandApplication->getReceiverTime(),
                    'confirm_name' => $demandApplication->getConfirmName(),
                    'confirm_time' => (string)$demandApplication->getConfirmTime(),
                    'applicant_remark' => $demandApplication->getApplicantRemark(),
                    'status_text' => $demandApplication->getStatusText(),

                ];
            })->toArray();


        $header = ['ID', '项目标的', '申请人', '所属人','申请时间', '有无合同', '合同编号', '节目数量', '节目列表',
            '期望上线日期', '期望接单人', '投放地点备注', '大屏节目需求','小屏定制内容', 'H5节目需求', '其他定制内容',
            '接单人', '接单人备注', '接单时间', '确定完成人', '确定完成时间', '申请备注', '申请状态'];


        $this->header_num = count($header);
        array_unshift($demandApplications, $header, $header);
        $this->data = $data = collect($demandApplications);

        return $data;
    }


}