<?php

namespace App\Console\Commands;

use App\Services\BevyEventService;
use Illuminate\Console\Command;

class SyncBevyEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bevy:sync-events {--quick : Skip fetching individual event details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize GDGoC MMU events from the official Bevy platform (gdg.community.dev)';

    /**
     * Execute the console command.
     */
    public function handle(BevyEventService $service)
    {
        $this->info('Connecting to Bevy platform for Chapter 2886 (GDGoC MMU)...');

        $quick = $this->option('quick');
        $result = $service->syncEvents(!$quick);

        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        }

        $this->error($result['message']);
        return Command::FAILURE;
    }
}

