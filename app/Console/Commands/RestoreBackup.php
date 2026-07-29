<?php

namespace Content\App\Console\Commands;

use Content\App\Services\SitemapService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:module:backup:restore')]
#[Description('Restore content resources from backup')]
class RestoreBackup extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(SitemapService::class)->restoreFiles();

    }


    private function setPid()
    {
        file_put_contents($this->pid(), time(), LOCK_EX);
    }

    private function pid()
    {
        return base_path('.content.pid');
    }
}
