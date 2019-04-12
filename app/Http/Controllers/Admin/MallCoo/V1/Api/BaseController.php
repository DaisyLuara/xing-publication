<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\MallCooRequest;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $mall_coo;

    /**
     * BaseController constructor.
     * @param MallCooRequest $request
     */
    public function __construct(MallCooRequest $request)
    {
        $this->mall_coo = app('mall_coo')->setMallCooConfig($request->oid);
    }

}
