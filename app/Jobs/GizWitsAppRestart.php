<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GizWitsAppRestart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $device_id;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_id)
    {
        $this->device_id = $device_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app('giz_wits')->restart($this->device_id);
    }
}
