<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFaceCollect;
use App\Http\Controllers\Admin\MallCoo\V1\Models\TodayFileUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
     * Create a new job instance.
     *
     * @param int $qiniu_id
     * @param string $mobile
     */
    public function __construct(int $qiniu_id, string $mobile)
    {
        $this->qiniu_id = $qiniu_id;
        $this->mobile = $mobile;
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
            $this->mall_coo->bindUserFace($faceCollect, $this->mobile);
        }
    }
}
