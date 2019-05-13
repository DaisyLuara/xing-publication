<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Qiniu\Auth;

class MediaCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;

    /**
     * Create a new job instance.
     *
     * @param ActivityMedia $media
     */
    public function __construct(ActivityMedia $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var ActivityMedia $media */
        $media = $this->media;
        $method = 'POST';
        $url = 'http://ai.qiniuapi.com/v3/image/censor';
        $host = 'ai.qiniuapi.com';
        $contentType = 'application/json';
        $body = '{ "data": { "uri": ' . $media->url . '} ,"params":{"scenes":["pulp","terror","politician"]} }';

        $auth = new Auth(config('filesystems.disks.qiniu_yq.access_key'), config('filesystems.disks.qiniu_yq.secret_key'));
        $headers = $auth->authorizationV2($url, $method, $body, $contentType);
        $headers['Content-Type'] = $contentType;
        $headers['Host'] = $host;
        $response = \Qiniu\Http\Client::post($url, $body, $headers)->json();

        $status = 2;
        if ($response['code'] === 200 && $response['result']['suggestion'] === 'block') {
            $status = 0;
        }
        $media->update(['status' => $status]);
    }
}
