<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Log;

class ShortUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $shortUrlID;
    protected $browserInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $shortUrlID, array $browserInfo)
    {
        $this->shortUrlID = $shortUrlID;
        $this->browserInfo = $browserInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shortUrl = ShortUrl::query()->findOrFail($this->shortUrlID);

        $queryParams = parse_query(parse_url($shortUrl->target_url, PHP_URL_QUERY));
        $data = array_merge(['short_url_id' => $shortUrl->id], $queryParams);
        $data = array_merge($data, $this->browserInfo);

        if (isset($queryParams['id'])) {
            $data['third_id'] = $queryParams['id'];
        }

        ShortUrlRecords::create($data);
    }
}
