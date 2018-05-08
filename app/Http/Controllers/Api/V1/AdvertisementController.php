<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Advertisement;
use App\Transformers\AdvertisementTransformer;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request, Advertisement $advertisement)
    {
        $query = $advertisement->query();
        $advertisement = $query->orderBy('atid', 'desc')
            ->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($advertisement, new AdvertisementTransformer());
    }

    public function query(Request $request, Advertisement $advertisement)
    {
        $query = $advertisement->query();
        $advertisement = collect();
        if (!$request->has('atiid') && !$request->has('name')) {
            return $this->response->collection($advertisement, new AdvertisementTransformer());
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('atiid')) {
            $query->where('atiid', '=', $request->atiid);
        }
        $advertisement = $query->orderBy('date', 'desc')->get();
        return $this->response->collection($advertisement, new AdvertisementTransformer());
    }
}
