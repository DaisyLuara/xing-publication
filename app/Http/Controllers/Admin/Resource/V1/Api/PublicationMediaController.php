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
use App\Http\Controllers\Admin\Resource\V1\Request\PublicationMediaRequest;
use App\Http\Controllers\Admin\Resource\V1\Transformer\PublicationMediaTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;

class PublicationMediaController extends Controller
{
    public function index(PublicationMedia $publicationMedia)
    {
        $query = $publicationMedia->query();
        $publicationMedia = $query->paginate(10);
        return $this->response()->paginator($publicationMedia, new PublicationMediaTransformer())->setStatusCode(200);
    }

    /**
     * 存储publication资源
     * @param PublicationMediaRequest $request
     * @param PublicationMedia $publicationMedia
     * @return \Dingo\Api\Http\Response
     */
    public function store(PublicationMediaRequest $request, PublicationMedia $publicationMedia): Response
    {
        $disk = \Storage::disk('qiniu');
        $domain = $disk->getDriver()->downloadUrl();
        $data = [
            'name' => $request->get('name'),
            'url' => $domain . urlencode($request->get('key')),
            'size' => $request->get('size'),
            'height' => 0,
            'width' => 0,
        ];
        $media = Media::create($data);
        $publicationMedia->fill(['media_id' => $media->id])->save();
        return $this->response()->item($publicationMedia, new PublicationMediaTransformer())->setStatusCode(201);
    }

    public function update(PublicationMediaRequest $request, PublicationMedia $publicationMedia)
    {
        $publicationMedia->media()->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }
}