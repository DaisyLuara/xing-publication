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
        if ($request->has('atid')) {
            $query->where('atid', '=', $request->atid);
        }
        if ($request->has('atiid')) {
            $query->where('atiid', '=', $request->atiid);
        }
        $advertisement = $query->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($advertisement, new AdvertisementTransformer());
    }
}
