<?php

namespace App\Http\Controllers\Api\V1\GizWits;

use App\Http\Controllers\Api\V1\BaseController;
use App\Libraries\GizWits\GizWitsClient;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\SendGizWitsWarning;
use Illuminate\Http\Request;
use App\Models\WxWarning;
use Carbon\Carbon;
use DB;

class AppController extends BaseController
{
    protected $schedule;
    /** @var GizWitsClient */
    protected $giz_wits_client;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->giz_wits_client = app('giz_wits');
    }

    public function start($did)
    {
        return response()->json($this->giz_wits_client->start($did));
    }

    public function stop($did)
    {
        return response()->json($this->giz_wits_client->stop($did));
    }

    public function restart($did)
    {
        return response()->json($this->giz_wits_client->restart($did));
    }

    public function bindings()
    {
        return response()->json($this->giz_wits_client->bindings());
    }

    public function latest($did)
    {
        return response()->json($this->giz_wits_client->latest($did));
    }

    public function deviceDetail($did)
    {
        return response()->json($this->giz_wits_client->deviceDetail($did));
    }

    public function schedule(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required',
        ]);
        Carbon::now()->addMinute(2)->toDateTimeString();
    }

    public function getTagUsers()
    {
        /** @var \EasyWeChat\OfficialAccount\Application $official_account */
        $official_account = app('wechat.official_account');
        $tags = $official_account->user_tag->list();

        $open_ids = [];
        foreach ($tags['tags'] as $tag) {
            if ($tag['count'] > 1) {
                $res = $official_account->user_tag->usersOfTag($tag['id']);
                $open_ids[] = $res['data']['openid'];
            }
        }
        return response()->json($open_ids);
    }

    public function warning(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'oid' => 'required',
            'project' => 'required',
            'push_id' => 'required',
        ]);

        $message = explode(PHP_EOL, $request['message']);
        $input = [];
        if (count($message) == 1) {
            $input['address'] = $message[0];
        } elseif (count($message) == 2) {
            $input['address'] = $message[0];
            $input['reason'] = $message[1];
        } elseif (count($message) == 3) {
            $input['address'] = $message[0];
            $input['reason'] = $message[2];
        }

        WxWarning::create(array_merge($request->all(),$input));

        $open_ids = $this->giz_wits_client->getOpenIds();

        $type = $request->has('type') ? $request->get('type') : 1;
        if ($type == 2) {
            $open_ids = array_diff($open_ids, [
                "oLJ6cwmrTsQ58jRTpYMUexjhlNq4",
                "oLJ6cwsvc5BIZZENIEyiTHujPLb8",
                "oLJ6cwtB3Ni5MpWxk0ENgqJzIlC0",
            ]);
        }

        $template_id = $this->giz_wits_client->getTemplateId();
        foreach ($open_ids as $open_id) {
            SendGizWitsWarning::dispatch($open_id, $request->message, $template_id, $request->get('oid'), $request->get('project'), $request->get('push_id'))->onQueue('warning');

        }

        return response()->json();
    }
}
