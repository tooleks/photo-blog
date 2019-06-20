<?php

namespace Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class GenerateRestApiDocumentation.
 *
 * @package Console\Commands
 */
class GenerateRestApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:rest-api-documentation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate REST API documentation';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $command = implode(' ', [
            base_path('node_modules/.bin/apidoc'),
            '-i',
            './src/api/',
            '-o',
            './docs/rest_api/dist',
        ]);

        $process = new Process($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->comment($process->getOutput());
    }
}
