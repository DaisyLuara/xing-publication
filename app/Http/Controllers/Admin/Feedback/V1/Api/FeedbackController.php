<?php

namespace App\Http\Controllers\Admin\Feedback\V1\Api;

use App\Http\Controllers\Admin\Feedback\V1\Models\Feedback;
use App\Http\Controllers\Admin\Feedback\V1\Request\FeedbackRequest;
use App\Http\Controllers\Admin\Feedback\V1\Transformer\FeedbackTransformer;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FeedbackController extends Controller
{
    public function index(Request $request, Feedback $feedback): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $query = $feedback->query();

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->get('title')) {
            $query->where('title', 'like', '%' . $request->get('title') . '%');
        }

        if ($user->hasRole('user|bd-manager')) {
            $customer_ids = Customer::query()->whereHas('company', static function ($c) use ($user) {
                $c->whereHas('bdUser', function ($bd) use ($user) {
                    $bd->whereRaw("(id = {$user->id} or parent_id = {$user->id})");
                });
            })->pluck('id')->toArray();

            $query->where('createable_type', Customer::class)
                ->whereIn('createable_id', $customer_ids);
        }

        $company_name = $request->get('company_name');
        if ($company_name) {
            $customer_ids = Customer::query()->whereHas('company', static function ($c) use ($company_name) {
                $c->where('name', 'like', '%' . $company_name . '%');
            })->pluck('id')->toArray();

            $query->where('createable_type', Customer::class)
                ->whereIn('createable_id', $customer_ids);
        }


        $feedbackItems = $query->where('parent_id', 0)
            ->orderByDesc('id')->paginate(10);

        return $this->response()->paginator($feedbackItems, new FeedbackTransformer());
    }


    public function show(Feedback $feedback): Response
    {
        if ($feedback->parent_id !== 0) {
            abort(422, '该条反馈非问题');
        }

        return $this->response()->item($feedback, new FeedbackTransformer());
    }


    public function store(FeedbackRequest $request, Feedback $feedback): Response
    {

        /** @var User $user */
        $user = Auth::user();

        /** @var Feedback $parentFeedback */
        $parentFeedback = Feedback::query()->find($request->get('parent_id'));

        /** @var Feedback $topFeedback */
        $topFeedback = $parentFeedback->top_parent_id === 0 ? $parentFeedback : $parentFeedback->top_parent;
        if ($user->hasRole('user|bd-manager') && $topFeedback) {
            $bd_user = $topFeedback->createable && $topFeedback->createable->company ?
                $topFeedback->createable->company->bdUser : null;

            if (!$bd_user || ($bd_user->id !== $user->id && $bd_user->parent_id !== $user->id)) {
                abort('您无权回答该反馈');
            }
        }

        $photo_media_ids = $request->get('photo_media_ids') ?? [];
        if ($photo_media_ids) {
            $photo_ids = Media::query()->whereIn('id', $photo_media_ids)
                ->pluck('id')->toArray();

            if (array_diff($photo_media_ids, $photo_ids)) {
                abort('上传的照片中有不存在的媒体文件');
            }
        }

        $newFeedback = $feedback->query()->create([
            'title' => $request->get('title') ?? '无标题',
            'content' => $request->get('content'),
            'createable_id' => $user->id,
            'createable_type' => User::class,
            'parent_id' => $parentFeedback->id,
            'top_parent_id' => $parentFeedback->top_parent_id > 0 ? $parentFeedback->top_parent_id : $parentFeedback->id,
            'video_media_id' => $request->video_media_id ?? null,
            'status' => Feedback::NO_STATUS
        ]);

        $parentFeedback->status = Feedback::STATUE_DEALT;
        $parentFeedback->save();

        if ($photo_media_ids) {
            $photo_media_pivot = [];
            for ($i = 0, $iMax = count($photo_media_ids); $i < $iMax; $i++) {
                $photo_media_pivot[] = ['type' => 'feedback', 'recorder_id' => $user->id];
            }
            $newFeedback->photos()->attach(array_combine($photo_media_ids, $photo_media_pivot));
        }

        activity('response_feedback')
            ->causedBy($user)
            ->performedOn($newFeedback)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('回复反馈');

        return $this->response()->item($newFeedback, new FeedbackTransformer());

    }


}
