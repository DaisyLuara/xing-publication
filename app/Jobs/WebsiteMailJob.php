<?php

namespace App\Jobs;

use App\Mail\WebVisitor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WebsiteMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail;
    protected $visitor;

    /**
     * Create a new job instance.
     *
     * @param $mail
     * @param $visitor
     */
    public function __construct($mail, $visitor)
    {
        $this->mail = $mail;
        $this->visitor = $visitor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        \Mail::to($this->mail)->send(new WebVisitor($this->visitor));
    }
}
