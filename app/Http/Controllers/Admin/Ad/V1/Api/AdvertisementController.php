<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdvertisementTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdvertisementRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use App\Http\Controllers\Controller;
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request, Advertisement $advertisement): Response
    {
        $query = $advertisement->query();

        if ($request->get('ad_trade_id')) {
            $query->where('atid', '=', $request->get('ad_trade_id'));
        }

        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->get('type')) {
            $query->where('type', '=', $request->get('type'));
        }

        if ($request->get('isad') !== null) {
            $query->where('isad', '=', $request->get('isad'));
        }

        if ($request->get('pass') !== null) {
            $query->where('pass', '=', $request->get('pass'));
        }

        $advertisements = $query->orderBy('aid', 'desc')
            ->paginate(10);

        return $this->response->paginator($advertisements, new AdvertisementTransformer());
    }

    public function show(Advertisement $advertisement): Response
    {
        return $this->response->item($advertisement, new AdvertisementTransformer());
    }

    public function store(AdvertisementRequest $request, Advertisement $advertisement): Response
    {
        $data = $request->all();
        /** @var User $user */
        $user = $this->user();

        //需要获取link的size
        $content = file_get_contents($data['link']);

        $addParams = [
            'size' => strlen($content)
        ];

        if ($user->z) {
            $addParams['z'] = $user->z;
        }

        if (in_array($data['type'], ['static', 'gif'])) {
            $data['img'] = $data['link'];
        } else if ($data['type'] === 'video') {
            $data['img'] = 'http://image.xingstation.cn/1007/image/426_video.jpg';
        } else {
            $data['img'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        $advertisement->fill(array_merge($data, $addParams))->save();

        activity('create_ad')
            ->causedBy($user)
            ->performedOn($advertisement)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增广告素材');

        return $this->response->noContent();
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement): Response
    {
        $data = $request->all();
        /** @var User $user */
        $user = $this->user();

        //需要获取link的size
        $content = file_get_contents($data['link']);

        if (in_array($data['type'], ['static', 'gif'])) {
            $data['img'] = $data['link'];
        } else if ($data['type'] === 'video') {
            $data['img'] = 'http://image.xingstation.cn/1007/image/426_video.jpg';
        } else {
            $data['img'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        $advertisement->fill(array_merge($data, [
            'size' => strlen($content),
        ]))->save();

        activity('update_ad')
            ->causedBy($user)
            ->performedOn($advertisement)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $data])
            ->log('编辑广告素材');

        return $this->response->noContent();
    }

}
