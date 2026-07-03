<?php

namespace Content\App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:module:test-command')]
#[Description('Elymod App Test command')]
class TestCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "Content is mounted\n";
    }
}
