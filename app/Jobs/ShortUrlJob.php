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
    protected $short_url_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $short_url_id)
    {
        $this->short_url_id = $short_url_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->short_url_id);
        $shortUrl = ShortUrl::query()->findOrFail($this->short_url_id);
        $queryParams = parse_query(parse_url($shortUrl->target_url, PHP_URL_QUERY));
        $data = array_merge(['short_url_id' => $shortUrl->id], $queryParams);
        Log::info('data', ['data' => $data]);
        ShortUrlRecords::create($data);
    }
}
