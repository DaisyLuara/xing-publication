<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Console\Commands\FaceCharacterCount;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class FaceCountController extends Controller
{
    public function aaa()
    {
        $startDate = '2018-03-01 00:00:00';
        $endDate = '2018-03-30 23:59:59';
        dd(strtotime($startDate) * 1000, strtotime($endDate) * 1000);
    }
}
