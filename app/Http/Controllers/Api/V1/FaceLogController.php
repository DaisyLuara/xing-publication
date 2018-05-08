<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FaceLog;
use App\Transformers\FaceLogTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FaceLogController extends Controller
{
    public function index(Request $request, FaceLog $faceLog)
    {
        $date_start = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7);
        $date_end = $request->has('end_date') ? $request->end_date : Carbon::now();
        $belong = $request->has('belong') ? $request->belong : 'all';
        $type = $request->has('type') ? $request->type : 'looker';

        $query = $faceLog->query();

        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        if ($arUserId) {
            $query->whereHas('pointArUser', function ($q) use ($arUserId) {
                $q->where('uid', '=', $arUserId);
            });
        }
        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }

        $faceLog = $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'")
            ->where('type', '=', $type)
            ->where('belong', '=', $belong)
            ->selectRaw('sum(gnum) as gnum,sum(bnum) as bnum,
            sum(age10b+age10g) as age10,sum(age18b+age18g) as age18,sum(age30b+age30g) as age30,
            sum(age40b+age40g) as age40,sum(age60b+age60g) as age60,sum(age61b+age61g) as age61')
            ->first();

        return $this->response->item($faceLog, new FaceLogTransformer());
    }

}
