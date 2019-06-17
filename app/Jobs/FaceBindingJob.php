<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig;
use App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFaceCollect;
use App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFileUpload;
use App\Support\MallCoo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class FaceBindingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    private $qiniu_id;
    /**
     * @var string
     */
    private $mobile;
    /**
     * @var string
     */
    private $marketid;



    /**
     * Create a new job instance.
     *
     * @param int $qiniu_id
     * @param string $mobile
     */
    public function __construct(int $qiniu_id, string $mobile, int $marketid)
    {
        $this->qiniu_id = $qiniu_id;
        $this->mobile = $mobile;
        $this->marketid = $marketid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var TodayFileUpload $fileUpload */
        $fileUpload = TodayFileUpload::query()->findOrFail($this->qiniu_id);
        /** @var TodayFaceCollect $faceCollect */
        $faceCollect = TodayFaceCollect::query()->where('fpid', $fileUpload->fpid)->orderByDesc('clientdate')->first();

        if ($faceCollect) {
            $config = MallcooConfig::query()->where('marketid', $this->marketid)->firstOrFail();
            $mallcoo = new MallCoo($config['mallcoo_mall_id'], $config['mallcoo_appid'], $config['mallcoo_public_key'], $config['mallcoo_private_key']);
            $result = $mallcoo->bindUserFace($faceCollect, $this->mobile);
            Log::info('face-bind', ['result' => $result]);
        }
    }
}
