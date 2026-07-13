<?php

namespace Content\App\Console\Commands;

use Content\App\Services\PageService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:module:copy:files')]
#[Description('Command description')]
class copyFiles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(PageService::class)->copyFiles();
    }
}
