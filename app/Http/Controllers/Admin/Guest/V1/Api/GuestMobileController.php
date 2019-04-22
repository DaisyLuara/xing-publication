<?php

namespace App\Http\Controllers\Admin\Guest\V1\Api;

use App\Http\Controllers\Admin\Guest\V1\Models\GuestMobile;
use App\Http\Controllers\Admin\Guest\V1\Transformer\GuestMobileTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class GuestMobileController extends Controller
{
    public function index(Request $request, GuestMobile $guestMobile): Response
    {

        $query = $guestMobile->query();
        if ($request->get('mobile')) {
            $query->where('mobile', 'like', '%' . $request->get('mobile') . '%');
        }
        $guestMobilePagination = $query->orderByDesc('id')->paginate(10);
        return $this->response()->paginator($guestMobilePagination, new GuestMobileTransformer());

    }
}
