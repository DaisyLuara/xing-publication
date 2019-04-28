<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/28
 * Time: 上午9:50
 */

namespace App\Http\Controllers\Admin\Auditing\V1\Api;


use App\Http\Controllers\Admin\Auditing\V1\Request\ActivityMediaRequest;
use App\Http\Controllers\Admin\Media\V1\Models\ActivityMedia;
use App\Http\Controllers\Admin\Media\V1\Transformer\ActivityMediaTransformer;
use App\Http\Controllers\Controller;
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
        $media = $query->orderBy('created_at')->paginate(10);
        return $this->response()->paginator($media, new ActivityMediaTransformer())->setStatusCode(200);
    }

    public function audit(ActivityMediaRequest $request, ActivityMedia $media)
    {
        /** @var User $user */
        $user = $this->user();
        $media->status = $request->get('status');
        $media->audit_user_id = $user->id;
        $media->update();
        return $this->response()->noContent()->setStatusCode(200);
    }
}