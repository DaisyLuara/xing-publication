<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaceCountController extends Controller
{
    public function timeStamp(Request $request)
    {
        $date = $request->date;
        $startClientdate = strtotime($date . ' 00:00:00') * 1000;
        $endClientdate = strtotime($date . ' 23:59:59') * 1000;
        return response()->json(['start_clientdate' => $startClientdate, 'end_clientdate' => $endClientdate]);
    }
}
