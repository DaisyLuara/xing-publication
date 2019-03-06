<?php

namespace App\Http\Controllers\Admin\Demand\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Demand\V1\Request\DemandApplicationRequest;
use App\Http\Controllers\Admin\Demand\V1\Transformer\DemandApplicationTransformer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DemandApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = DemandApplication::query();

        if ($request->get("title")) {
            $query->where('title', 'like', "%" . $request->get("title") . "%");
        }

        if ($request->has("applicant_id") && $request->get("applicant_id")) {
            $query->where('applicant_id', '=', $request->get("applicant_id"));
        }

        if ($request->has("status")) {
            $query->where('status', '=', $request->get("status") ?? 0);
        }

        if ($request->has("receiver_id") && $request->get("receiver_id")) {
            $query->where('receiver_id', '=', $request->get("receiver_id"));
        }

        if ($request->get("create_start_date") && $request->get("create_end_date")) {
            $query->whereRaw("date_format(created_at, '%Y-%m-%d') between '$request->create_start_date' and '$request->create_end_date'");
        }

        $demandApplications = $query->orderBy("id", "desc")->paginate(10);

        return $this->response->paginator($demandApplications, new DemandApplicationTransformer());


    }

    public function show(Request $request, DemandApplication $demandApplication)
    {
        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }

    /**
     * 新增需求申请
     *
     * @param DemandApplicationRequest $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function store(DemandApplicationRequest $request, DemandApplication $demandApplication)
    {
        $params = $request->all();

        $params['applicant_id'] = Auth::user()->id;
        $params['expect_receiver_ids'] = implode(",", $params['expect_receiver_ids']);
        $params['status'] = DemandApplication::STATUS_UN_RECEIVE;

        //查询所选合同是否为已审批合同
        $contracts = Contract::query()->whereIn('id', $params['contract_ids'])
            ->where("status", 3)->get();
        if (count($contracts) != count($params['contract_ids'])) {
            abort(422, "列表中存在不合法的合同");
        }

        //保存需求申请
        $demandApplication->fill($params)->save();

        //更新与合同的关联
        $demandApplication->contracts()->sync($params['contract_ids']);

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }

    /**
     * 更新需求申请
     * @param DemandApplicationRequest $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function update(DemandApplicationRequest $request, DemandApplication $demandApplication)
    {

        $params = $request->all();

        if ($demandApplication->getStatus() != DemandApplication::STATUS_UN_RECEIVE) {
            abort(422, "状态不是未接单，无法修改");
        }

        if ($demandApplication->getApplicantId() != Auth::user()->id) {
            abort(422, "该申请非您创建，无权修改");
        }

        //查询所选合同是否为已审批合同
        $contracts = Contract::query()->whereIn('id', $params['contract_ids'])
            ->where("status", 3)->get();
        if (count($contracts) != count($params['contract_ids'])) {
            abort(422, "列表中存在不合法的合同");
        }


        $update_params = [
            'title' => $params['title'],
            'launch_point_remark' => $params['launch_point_remark'],
            'has_contract' => $params['has_contract'],
            'project_num' => $params['project_num'],
            'similar_project_name' => $params['similar_project_name'],
            'expect_online_time' => $params['expect_online_time'],
            'expect_receiver_ids' => implode(",", $params['expect_receiver_ids']),
            'big_screen_demand' => $params['big_screen_demand'],
            'h5_demand' => $params['h5_demand'],
            'other_demand' => $params['other_demand'],
            'applicant_remark' => $params['applicant_remark']
        ];


        //保存需求申请
        $demandApplication->update($update_params);

        //更新与合同的关联
        $demandApplication->contracts()->sync($params['contract_ids']);

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }


    /**
     * 接单
     * @param Request $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function receiveDemand(Request $request, DemandApplication $demandApplication)
    {
        $user = Auth::user();

        if ($demandApplication->getStatus() != DemandApplication::STATUS_UN_RECEIVE) {
            abort(422, "状态不是未接单，无法接单");
        }

        if ($user->can("demand.application.receive_special")) {
            $receiver = User::query()->findOrFail($request->get("receiver_id"));
            if (!$receiver->can("demand.application.receive")) {
                abort(422, "选择的接单人无权接单！");
            }
        } else {
            $receiver = $user;
        }

        $update_params = [
            'status' => DemandApplication::STATUS_RECEIVED,
            'receiver_id' => $receiver->id,
            'receiver_name' => $receiver->name,
            'receiver_remark' => $request->get("receiver_remark"),
            'receiver_time' => date("Y-m-d H:i:s"),
        ];

        //保存需求申请
        $demandApplication->update($update_params);

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }


    /**
     * 确认完成
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function confirmDemand(DemandApplication $demandApplication)
    {
        $user = Auth::user();

        if ($demandApplication->getStatus() != DemandApplication::STATUS_RECEIVED
            && $demandApplication->getStatus() != DemandApplication::STATUS_MODIFY
        ) {
            abort(422, "该状态无法确认完成");
        }

        if ($demandApplication->getApplicantId() != $user->id) {
            abort(422, "该申请非您创建，无权确认完成");
        }


        $update_params = [
            'status' => DemandApplication::STATUS_CONFIRM,
            'confirm_id' => $user->id,
            'confirm_name' => $user->name,
            'confirm_time' => date("Y-m-d H:i:s"),
        ];

        //保存需求申请
        $demandApplication->update($update_params);

        return $this->response->item($demandApplication, new DemandApplicationTransformer());


    }

}
