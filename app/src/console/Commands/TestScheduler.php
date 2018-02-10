<?php

namespace Console\Commands;

use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $logger
     * @return void
     */
    public function handle(LoggerInterface $logger): void
    {
        $logger->info('The scheduler is running.');
    }
}
