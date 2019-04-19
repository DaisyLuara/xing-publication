<?php

namespace App\Http\Controllers\Admin\Demand\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Demand\V1\Request\DemandApplicationRequest;
use App\Http\Controllers\Admin\Demand\V1\Transformer\DemandApplicationTransformer;
use App\Http\Controllers\Controller;
use App\Jobs\DemandApplicationNotificationJob;
use App\Models\User;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandApplicationController extends Controller
{
    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        $query = DemandApplication::query();
        /** @var User $user */
        $user = Auth::user();
        if ($request->get('title')) {
            $query->where('title', 'like', '%' . $request->get('title') . '%');
        }

        if ($request->has('applicant_id') && $request->get('applicant_id')) {
            $query->where('applicant_id', '=', $request->get('applicant_id'));
        }

        if ($request->has('status')) {
            $query->where('status', '=', $request->get('status') ?? 0);
        }

        if ($request->has('receiver_id') && $request->get('receiver_id')) {
            $query->where('receiver_id', '=', $request->get('receiver_id'));
        }

        if ($request->get('create_start_date') && $request->get('create_end_date')) {
            $query->whereRaw("date_format(created_at, '%Y-%m-%d') between '"
                . $request->get('create_start_date') . "' and '" . $request->get('create_end_date') . "'");
        }

        if ($user->hasRole('bd-manager')) {
            //BD主管可查看自己及下属BD新建的申请列表
            $user_ids = $user->subordinates()->pluck('id')->toArray();
            $user_ids[] = $user->id;
            $query->whereIn('applicant_id', $user_ids);
        } else if ($user->hasRole('user') || $user->hasRole('business-operation')) {
            //只能查询自己创建的 Application
            $query->where('applicant_id', '=', $user->id);
        }


        $demandApplications = $query->orderBy('id', 'desc')->paginate(10);

        return $this->response->paginator($demandApplications, new DemandApplicationTransformer());


    }

    public function show(DemandApplication $demandApplication): Response
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
    public function store(DemandApplicationRequest $request, DemandApplication $demandApplication): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $params = $request->all();

        $params['applicant_id'] = $user->id;
        $params['expect_receiver_ids'] = implode(',', $params['expect_receiver_ids']);
        $params['status'] = DemandApplication::STATUS_UN_RECEIVE;
        $params['contract_ids'] = $params['contract_ids'] ?? [];

        //查询所选合同是否为已审批合同
        if ($params['has_contract'] === DemandApplication::HAS_CONTRACT_YSE) {
            $contracts = Contract::query()->whereIn('id', $params['contract_ids'])
                ->where('status', 3)->get();
            if (count($contracts) !== count($params['contract_ids'])) {
                abort(422, '列表中存在不合法的合同');
            }
        } else {
            $params['contract_ids'] = [];
        }

        //保存需求申请
        $demandApplication->fill($params)->save();

        //更新与合同的关联
        $demandApplication->contracts()->sync($params['contract_ids']);

        DemandApplicationNotificationJob::dispatch($demandApplication, 'create')->onQueue('demand');
        DemandApplicationNotificationJob::dispatch($demandApplication, 'un_receive')->onQueue('demand')
            ->delay(now()->addHours(12));

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }

    /**
     * 更新需求申请
     * @param DemandApplicationRequest $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function update(DemandApplicationRequest $request, DemandApplication $demandApplication): Response
    {

        /** @var User $user */
        $user = Auth::user();

        $params = $request->all();
        $params['contract_ids'] = $params['contract_ids'] ?? [];

        if ($demandApplication->getStatus() !== DemandApplication::STATUS_UN_RECEIVE) {
            abort(422, '状态不是未接单，无法修改');
        }

        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '该申请非您创建，无权修改');
        }

        //查询所选合同是否为已审批合同
        if ($params['has_contract'] === DemandApplication::HAS_CONTRACT_YSE) {
            $contracts = Contract::query()->whereIn('id', $params['contract_ids'])
                ->where('status', 3)->get();
            if (count($contracts) !== count($params['contract_ids'])) {
                abort(422, '列表中存在不合法的合同');
            }
        } else {
            $params['contract_ids'] = [];
        }


        $update_params = [
            'title' => $params['title'],
            'launch_point_remark' => $params['launch_point_remark'],
            'has_contract' => $params['has_contract'],
            'project_num' => $params['project_num'],
            'similar_project_name' => $params['similar_project_name'],
            'expect_online_time' => Carbon::parse($params['expect_online_time'])->timezone('PRC')->toDateString(),
            'expect_receiver_ids' => implode(',', $params['expect_receiver_ids']),
            'big_screen_demand' => $params['big_screen_demand'],
            'h5_demand' => $params['h5_demand'],
            'other_demand' => $params['other_demand'],
            'applicant_remark' => $params['applicant_remark']
        ];


        //保存需求申请
        $demandApplication->update($update_params);

        //更新与合同的关联
        $demandApplication->contracts()->sync($params['contract_ids']);

        DemandApplicationNotificationJob::dispatch($demandApplication, 'update')->onQueue('demand');

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }


    /**
     * 更新合同信息
     * @param DemandApplicationRequest $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function updateContract(DemandApplicationRequest $request, DemandApplication $demandApplication): Response
    {

        /** @var User $user */
        $user = Auth::user();

        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '该申请非您创建，无权修改');
        }

        if ($demandApplication->getHasContract() !== DemandApplication::HAS_CONTRACT_REVIEWING) {
            abort(422, '该申请非审批中的合同状态');
        }

        if (!$request->get('contract_ids')) {
            abort(422, '请选择合同编号');
        }

        //合同编号
        $params['contract_ids'] = $request->get('contract_ids');

        //查询所选合同是否为已审批合同
        $contracts = Contract::query()->whereIn('id', $params['contract_ids'])
            ->where('status', 3)->get();
        if (count($contracts) !== count($params['contract_ids'])) {
            abort(422, '列表中存在不合法的合同');
        }

        //保存需求申请
        $demandApplication->update(['has_contract' => DemandApplication::HAS_CONTRACT_YSE]);

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
    public function receiveDemand(Request $request, DemandApplication $demandApplication): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->can('demand.application.receive_special')) {
            if ($demandApplication->getStatus() === DemandApplication::STATUS_CONFIRM) {
                abort(422, '状态已完成，法务主管无法指派接单人接单');
            }

            $receiver = User::query()->findOrFail($request->get('receiver_id'));
            if (!$receiver->can('demand.application.receive')) {
                abort(422, '选择的接单人无权接单！');
            }

        } else {
            if ($demandApplication->getStatus() !== DemandApplication::STATUS_UN_RECEIVE) {
                abort(422, '状态不是未接单，无法接单');
            }

            $receiver = $user;
        }

        $update_params = [
            'status' => DemandApplication::STATUS_RECEIVED,
            'receiver_id' => $receiver->id,
            'receiver_name' => $receiver->name,
            'receiver_remark' => $request->get('receiver_remark'),
            'receiver_time' => date('Y-m-d H:i:s'),
        ];

        //保存需求申请
        $demandApplication->update($update_params);

        DemandApplicationNotificationJob::dispatch($demandApplication, 'received')->onQueue('demand');

        return $this->response->item($demandApplication, new DemandApplicationTransformer());
    }


    /**
     * 确认完成
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     */
    public function confirmDemand(DemandApplication $demandApplication): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if (!in_array($demandApplication->getStatus(), [DemandApplication::STATUS_RECEIVED, DemandApplication::STATUS_MODIFY], true)) {
            abort(422, '该状态无法确认完成');
        }

        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '该申请非您创建，无权确认完成');
        }


        $update_params = [
            'status' => DemandApplication::STATUS_CONFIRM,
            'confirm_id' => $user->id,
            'confirm_name' => $user->name,
            'confirm_time' => date('Y-m-d H:i:s'),
        ];

        //保存需求申请
        $demandApplication->update($update_params);

        DemandApplicationNotificationJob::dispatch($demandApplication, 'confirm')->onQueue('demand');

        return $this->response->item($demandApplication, new DemandApplicationTransformer());


    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'demand_application');
    }

}
