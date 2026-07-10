<?php

namespace Content\App\Jobs;



use Illuminate\Contracts\Queue\ShouldQueue;
use Content\App\Services\PageService;
use Illuminate\Foundation\Queue\Queueable;

class SitemapIndexJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(PageService::class)->indexPages();
    }
}
