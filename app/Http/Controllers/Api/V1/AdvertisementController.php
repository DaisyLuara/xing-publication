<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AdvertisementRequest;
use App\Models\Advertisement;
use App\Transformers\AdvertisementTransformer;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request, Advertisement $advertisement)
    {
        $query = $advertisement->query();
        if ($request->ad_trade_id) {
            $query->where('atid', '=', $request->ad_trade_id);
        }
        if ($request->advertiser_id) {
            $query->where('atiid', '=', $request->advertiser_id);
        }
        $advertisement = $query->orderBy('atid', 'desc')
            ->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($advertisement, new AdvertisementTransformer());
    }

    public function store(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->all();
        $name = $data['name'];
        $names = explode(PHP_EOL, $name);
        unset($data['name']);

        //需要获取link的size
        $query = $advertisement->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }

        return $this->response->noContent();
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->all();
        $aids = $request->aids;
        unset($data['aids']);

        $query = $advertisement->query();
        foreach ($aids as $aid) {
            $query->where('aid', '=', $aid)->update($data);
        }
        return $this->response->noContent();
    }

}
