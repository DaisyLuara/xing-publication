<?php

namespace App\Http\Controllers\Admin\Media\V1\Api;

use App\Http\Controllers\Admin\Media\V1\Models\MediaInfo;
use App\Http\Controllers\Admin\Media\V1\Request\MediaInfoRequest;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaInfoTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MediaInfoController extends Controller
{

    public function index(Request $request, MediaInfo $mediaInfo): \Dingo\Api\Http\Response
    {
        $query = $mediaInfo->query()->where('type', $request->type ?? 'project_operation');

        if ($request->has('name') && $request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('start_date') && $request->get('end_date')) {
            $query = $query->whereBetween('date', [
                Carbon::parse($request->get('start_date'))->toDateString(),
                Carbon::parse($request->get('end_date'))->toDateString()
            ]);
        }

        $mediaInfoPagination = $query->orderByDesc('id')->paginate(10);
        return $this->response()->paginator($mediaInfoPagination, new MediaInfoTransformer());

    }

    public function show(MediaInfo $mediaInfo): \Dingo\Api\Http\Response
    {
        return $this->response()->item($mediaInfo, new MediaInfoTransformer());
    }

    public function store(MediaInfoRequest $request, MediaInfo $mediaInfo)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $mediaInfo->fill(array_merge($request->all(), [
            'type' => $request->type ?? 'project_operation',
            'date' => Carbon::now('PRC')->toDateString(),
            'recorder_id' => $user->id
        ]))->save();

        return $this->response()->noContent()->setStatusCode(201);

    }

    public function update(MediaInfoRequest $request, MediaInfo $mediaInfo)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $params = $request->all();
        unset($params['type']);
        $mediaInfo->update(array_merge($params, [
            'date' => Carbon::now('PRC')->toDateString(),
            'recorder_id' => $user->id
        ]));
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(MediaInfoRequest $request, MediaInfo $mediaInfo)
    {
        $ids = $request->get('ids');
        $mediaInfo->query()->whereIn('id', $ids)->delete();
        return $this->response()->noContent()->setStatusCode(200);

    }

}
