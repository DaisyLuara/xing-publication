<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use Log;
use DB;

class SendGizWitsWarning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $open_id;
    protected $message;
    protected $template_id;
    protected $oid;
    protected $project;
    protected $push_id;
    public $tries = 1;

    public function __construct($open_id, $message, $template_id,$oid,$project,$push_id)
    {
        $this->open_id = $open_id;
        $this->message = $message;
        $this->template_id = $template_id;
        $this->oid = $oid;
        $this->project = $project;
        $this->push_id = $push_id;
    }

    public function handle()
    {
        /** @var \EasyWeChat\OfficialAccount\Application $official_account */

        $count = DB::table('wx_warnings')->where('created_at','>=',Carbon::now()->startOfDay())
            ->where('push_id',$this->push_id)
            ->selectRaw('type,count(push_id) as total')
            ->groupBy('type')
            ->get()
        ;

        $restart_success = 0;
        $restart_failed = 0;
        $count->each(function($item) use(&$restart_success,&$restart_failed){
            if($item->type == 2){
                $restart_success = $item->total;
            }

            if($item->type == 1){
                $restart_failed = $item->total;
            }

        });

        $official_account = app('wechat.official_account');
        $result = $official_account->template_message->send([
            'touser' => $this->open_id,
            'template_id' => $this->template_id,
            'url' => '',
            'data' => [
                'first' => $this->message,
                'keyword1' => $this->project,
                'keyword2' => $this->push_id,
                'keyword3' => Carbon::now()->toDateTimeString(),
                'remark' => '重启成功：'.$restart_success.';重启失败：'.$restart_failed,
            ]
        ]);

        /**
         * @todo 异常处理
         */

    }

    public function fail($exception = null)
    {
        Log::info($exception);
    }
}
