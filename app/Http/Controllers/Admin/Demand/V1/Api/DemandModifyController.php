<?php

namespace App\Http\Controllers\Admin\Demand\V1\Api;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use App\Http\Controllers\Admin\Demand\V1\Request\DemandModifyRequest;
use App\Http\Controllers\Admin\Demand\V1\Transformer\DemandModifyTransformer;
use App\Http\Controllers\Controller;
use App\Jobs\DemandModifyNotificationJob;
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandModifyController extends Controller
{
    /**
     * 需求修改列表
     * @param Request $request
     * @param DemandModify $demandModify
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, DemandModify $demandModify): Response
    {
        $query = $demandModify->query();
        /** @var User $user */
        $user = Auth::user();

        //需求申请
        if ($request->has('demand_application_id') && $request->get('demand_application_id')) {
            $query->where('demand_application_id', '=', $request->get('demand_application_id'));
        }

        //反馈意见
        if ($request->has('has_feedback')) {
            $query->where('has_feedback', '=', $request->get('has_feedback'));
        }

        //平台意见
        if ($request->has('status')) {
            $query->where('status', '=', $request->get('status'));
        }

        //创建时间
        if ($request->get('create_start_date') && $request->get('create_end_date')) {
            $query->whereRaw("date_format(created_at, '%Y-%m-%d') between '"
                . $request->get('create_start_date') . "' and '" . $request->get('create_end_date') . "'");
        }

        if (!$user->hasAnyPermission(['demand.modify.review'])) {

            $query->whereHas('demand_application', static function ($demand_application) use ($user) {
                //接单人看到自己接单的
                if ($user->hasAnyPermission(['demand.modify.feedback'])) {
                    $demand_application->where('receiver_id', '=', $user->id);
                } else if ($user->hasRole('bd-manager')) {
                    //BD主管可查看自己及下属BD新建的申请列表
                    $user_ids = $user->subordinates()->pluck('id')->toArray();
                    $user_ids[] = $user->id;
                    $demand_application->whereIn('applicant_id', $user_ids);
                } else if ($user->hasRole('user') || $user->hasRole('business-operation')) {
                    //只能查询自己创建的 Application
                    $demand_application->where('applicant_id', '=', $user->id);
                }

            });

        }

        $demandModifyPagination = $query->orderByDesc('id')->paginate(10);
        return $this->response->paginator($demandModifyPagination, new DemandModifyTransformer());
    }


    public function show(DemandModify $demandModify): Response
    {
        return $this->response->item($demandModify, new DemandModifyTransformer());
    }


    /**
     * 新建
     * @param DemandModifyRequest $request
     * @param DemandModify $demandModify
     * @return \Dingo\Api\Http\Response
     */
    public function store(DemandModifyRequest $request, DemandModify $demandModify): Response
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var DemandApplication $demandApplication */
        $demandApplication = DemandApplication::query()->findOrFail($request->get('demand_application_id'));
        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '选择的标的非您所创建');
        }
        $insertParams = [
            'demand_application_id' => $request->get('demand_application_id'),
            'applicant_id' => $user->id,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'status' => DemandModify::STATUS_UN_REVIEW
        ];

        $demandModify->fill($insertParams)->save();
        $demandApplication->update(['status' => DemandApplication::STATUS_MODIFY]);

        DemandModifyNotificationJob::dispatch($demandModify, 'create')->onQueue('demand');
        DemandModifyNotificationJob::dispatch($demandModify, 'un_review')->onQueue('demand')
            ->delay(now()->addHours(12));

        return $this->response->item($demandModify, new DemandModifyTransformer());
    }

    /**
     * 编辑
     * @param DemandModifyRequest $request
     * @param DemandModify $demandModify
     * @return \Dingo\Api\Http\Response
     */
    public function update(DemandModifyRequest $request, DemandModify $demandModify): Response
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var DemandApplication $demandApplication */
        $demandApplication = DemandApplication::query()->findOrFail($request->get('demand_application_id'));
        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '选择的标的非您所创建');
        }

        if ($demandModify->getStatus() !== DemandModify::STATUS_UN_REVIEW && $demandModify->getHasFeedback() !== false) {
            abort(422, '该状态无法修改');
        }

        if ($demandApplication->getApplicantId() !== $user->id) {
            abort(422, '该需求申请非您创建，无权修改');
        }

        $updateParams = [
            'demand_application_id' => $request->get('demand_application_id'),
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'status' => DemandModify::STATUS_UN_REVIEW
        ];

        $demandModify->update($updateParams);

        DemandModifyNotificationJob::dispatch($demandModify, 'update')->onQueue('demand');

        return $this->response->item($demandModify, new DemandModifyTransformer());
    }

    /**
     * 审核
     * @param Request $request
     * @param DemandModify $demandModify
     * @return \Dingo\Api\Http\Response
     */
    public function reviewDemandModify(Request $request, DemandModify $demandModify): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if ($demandModify->getStatus() !== DemandModify::STATUS_UN_REVIEW) {
            abort(422, '该状态无法审核');
        }


        $updateParams = [
            'reviewer_id' => $user->id,
            'reviewer_name' => $user->name,
            'review_time' => date('Y-m-d H:i:s'),
        ];

        if ($request->get('review')) {
            //审核通过
            $updateParams['status'] = DemandModify::STATUS_PASS;
        } else {
            //审核拒绝
            if (!$request->get('reject_remark')) {
                abort(422, '拒绝原因不能为空');
            }
            $updateParams['reject_remark'] = $request->get('reject_remark');
            $updateParams['status'] = DemandModify::STATUS_REJECT;
        }


        $demandModify->update($updateParams);

        DemandModifyNotificationJob::dispatch($demandModify, 'reviewed')->onQueue('demand');

        return $this->response->item($demandModify, new DemandModifyTransformer());

    }

    /**
     * 反馈
     * @param Request $request
     * @param DemandModify $demandModify
     * @return \Dingo\Api\Http\Response
     */
    public function feedbackDemandModify(Request $request, DemandModify $demandModify): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if ($demandModify->demand_application->receiver_id !== $user->id) {
            abort(422, '对应的项目标的非您的接单，您无法反馈');
        }

        if ($demandModify->getHasFeedback()) {
            abort(422, '该条记录已反馈');
        }

        if (!$request->get('feedback')) {
            abort(422, '反馈内容不能为空');
        }

        $updateParams = [
            'has_feedback' => true,
            'feedback' => $request->get('feedback'),
            'feedback_time' => date('Y-m-d H:i:s'),
            'feedback_person_id' => $user->id,
            'feedback_person_name' => $user->name,
        ];

        $demandModify->update($updateParams);

        DemandModifyNotificationJob::dispatch($demandModify, 'feedback')->onQueue('demand');

        return $this->response->item($demandModify, new DemandModifyTransformer());
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'demand_modify');
    }

}
