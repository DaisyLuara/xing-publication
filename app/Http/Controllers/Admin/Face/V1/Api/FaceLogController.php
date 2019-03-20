<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Admin\Face\V1\Transformer\FaceLogTransformer;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FaceLogController extends Controller
{
    public function index(Request $request, FaceLog $faceLog)
    {
        $startDate = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7);
        $endDate = $request->has('end_date') ? $request->end_date : Carbon::now();
        $belong = $request->has('alias') ? $request->alias : 'all';
        $type = $request->has('type') ? $request->type : 'looker';

        $query = $faceLog->query();

        $user = $this->user();
        $arUserZ = getArUserZ($user, $request);
        if ($arUserZ) {
            $query->whereHas('pointArUser', function ($q) use ($arUserZ) {
                $q->where('z', '=', $arUserZ);
            });
        }
        if ($request->has('point_id')) {
            $query->where('oid', '=', $request->point_id);
        }

        $faceLog = $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate'")
            ->where('type', '=', $type)
            ->where('belong', '=', $belong)
            ->selectRaw('sum(gnum) as gnum,sum(bnum) as bnum,
            sum(age10b+age10g) as age10,sum(age18b+age18g) as age18,sum(age30b+age30g) as age30,
            sum(age40b+age40g) as age40,sum(age60b+age60g) as age60,sum(age61b+age61g) as age61')
            ->first();

        return $this->response->item($faceLog, new FaceLogTransformer());
    }

}
