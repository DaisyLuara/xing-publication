<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/5
 * Time: 下午5:05
 */

namespace App\Http\Controllers\Admin\Resource\V1\Api;


use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMedia;
use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMediaGroup;
use App\Http\Controllers\Admin\Resource\V1\Request\PublicationMediaRequest;
use App\Http\Controllers\Admin\Resource\V1\Transformer\PublicationMediaTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class PublicationMediaController extends Controller
{
    public function index(Request $request, PublicationMediaGroup $group, PublicationMedia $publicationMedia)
    {
        $query = $publicationMedia->query();
        if ($request->has('name')) {
            $query->whereHas('media', static function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('name') . '%');
            });
        }
        $publicationMedia = $query->where('group_id', $group->id)->orderByDesc('id')->paginate(20);
        return $this->response()->paginator($publicationMedia, new PublicationMediaTransformer())->setStatusCode(200);
    }

    /**
     * 存储publication资源
     * @param PublicationMediaRequest $request
     * @param PublicationMediaGroup $group
     * @param PublicationMedia $publicationMedia
     * @return \Dingo\Api\Http\Response
     */
    public function store(PublicationMediaRequest $request, PublicationMediaGroup $group, PublicationMedia $publicationMedia): Response
    {
        $url = str_replace('+', '%20', urlencode($request->get('key')));
        $disk = \Storage::disk('qiniu');
        $info = $disk->getDriver()->imageInfo($url);
        $domain = $disk->getDriver()->downloadUrl();
        $data = [
            'name' => $request->get('name'),
            'url' => $domain . $url,
            'size' => $request->get('size'),
            'height' => $info['height'],
            'width' => $info['width'],
        ];
        $media = Media::create($data);
        $publicationMedia->fill(['group_id' => $group->id, 'media_id' => $media->id, 'creator' => $this->user()->id])->save();
        return $this->response()->item($publicationMedia, new PublicationMediaTransformer())->setStatusCode(201);
    }

    public function update(PublicationMediaRequest $request, PublicationMediaGroup $group, PublicationMedia $publicationMedia)
    {
        $publicationMedia->media()->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }
}