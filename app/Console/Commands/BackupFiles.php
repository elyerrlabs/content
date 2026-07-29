<?php

namespace Content\App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Content\App\Services\SitemapService;

#[Signature('content:module:backup')]
#[Description('Command description')]
class BackupFiles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(SitemapService::class)->backupFiles();
    }
}
