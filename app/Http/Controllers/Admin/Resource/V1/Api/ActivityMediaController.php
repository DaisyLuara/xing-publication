<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/28
 * Time: 上午9:50
 */

namespace App\Http\Controllers\Admin\Resource\V1\Api;


use App\Http\Controllers\Admin\Resource\V1\Models\Activity;
use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
use App\Http\Controllers\Admin\Resource\V1\Request\ActivityMediaRequest;
use App\Http\Controllers\Admin\Resource\V1\Transformer\ActivityMediaTransformer;
use App\Http\Controllers\Controller;
use App\Jobs\MediaCheckJob;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityMediaController extends Controller
{
    public function index(Request $request, ActivityMedia $media)
    {
        $query = $media->query();
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        $media = $query->orderByDesc('status')->orderByDesc('created_at')->paginate(10);
        return $this->response()->paginator($media, new ActivityMediaTransformer())->setStatusCode(200);
    }


    /**
     * 获取上传token
     * @return mixed
     */
    public function getToken()
    {
        $disk = \Storage::disk('qiniu_yq');
        $token = $disk->getDriver()->uploadToken();
        return $token;
    }

    /**
     * 活动文件存储
     * @param ActivityMediaRequest $request
     * @param ActivityMedia $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ActivityMediaRequest $request, ActivityMedia $media): \Illuminate\Http\JsonResponse
    {
        $disk = \Storage::disk('qiniu_yq');
        $domain = $disk->getDriver()->downloadUrl();
        $activity = Activity::query()->where('utm_campaign', $request->get('utm_campaign'))->first();
        $data = [
            'name' => $request->get('name'),
            'url' => $domain . urlencode($request->get('key')),
            'size' => $request->get('size'),
            'status' => 2,
            'activity_id' => $activity->id
        ];
        $media->fill($data)->save();
        //七牛鉴定
        MediaCheckJob::dispatch($media)->onQueue('media-check');
        return response()->json(['id' => $media->id, 'url' => $media->url])->setStatusCode(201);
    }

    /**
     * 活动文件审核
     * @param ActivityMediaRequest $request
     * @param ActivityMedia $media
     * @return \Dingo\Api\Http\Response
     */
    public function audit(ActivityMediaRequest $request, ActivityMedia $media): \Dingo\Api\Http\Response
    {
        /** @var User $user */
        $user = $this->user();
        $media->status = $request->get('status');
        $media->audit_user_id = $user->id;
        $media->update();
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function massAudit(ActivityMediaRequest $request)
    {
        $user = $this->user();
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            ActivityMedia::query()->where('id', $id)->update(['status' => $request->get('status'), 'audit_user_id' => $user->id]);
        }
        return $this->response()->noContent()->setStatusCode(200);
    }
}