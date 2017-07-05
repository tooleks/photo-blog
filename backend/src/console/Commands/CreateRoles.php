<?php

namespace Console\Commands;

use Core\Models\Role;
use Illuminate\Console\Command;

/**
 * Class CreateRoles.
 *
 * @package Console\Commands
 */
class CreateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!Role::exists()) {
            Role::insert([
                ['name' => Role::NAME_ADMINISTRATOR],
                ['name' => Role::NAME_CUSTOMER],
            ]);
        }
    }
}
