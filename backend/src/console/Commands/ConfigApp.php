<?php

namespace Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ConfigApp.
 *
 * @package Console\Commands
 */
class ConfigApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('storage:link');
        $this->call('migrate');
        $this->call('create:roles');
    }
}
