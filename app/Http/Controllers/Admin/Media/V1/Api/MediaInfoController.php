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

    public function index(Request $request, MediaInfo $mediaInfo)
    {
        $query = $mediaInfo->query();
        if ($request->has("name") && $request->name) {
            $query = $query->where("name", "like", "%" . $request->name . "%");
        }
        if ($request->start_time && $request->end_time) {
            $query = $query->whereBetween("date", [
                Carbon::parse($request->start_time)->toDateString(),
                Carbon::parse($request->end_time)->toDateString()
            ]);
        }
        $mediaInfo = $query->orderby("id", "desc")->paginate(10);
        return $this->response()->paginator($mediaInfo, new MediaInfoTransformer());

    }

    public function show(MediaInfo $mediaInfo)
    {
        return $this->response()->item($mediaInfo, new MediaInfoTransformer());
    }

    public function store(MediaInfoRequest $request, MediaInfo $mediaInfo)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $params = $request->all();
        $mediaInfo->fill(array_merge($params, [
            'type' => $params['type'] ?? 'project_operation',
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
        $ids = $request->get("ids");
        $mediaInfo->query()->whereIn('id', $ids)->delete();
        return $this->response()->noContent()->setStatusCode(200);

    }

}
