<?php

namespace Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Logging\Log as Logger;

/**
 * Class TestScheduler.
 *
 * @package Console\Commands
 */
class TestScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the scheduler';

    /**
     * Execute the console command.
     *
     * @param Logger $logger
     * @return void
     */
    public function handle(Logger $logger): void
    {
        $logger->info('The scheduler is running.');
    }
}
