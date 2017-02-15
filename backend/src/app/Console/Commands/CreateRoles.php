<?php

namespace App\Console\Commands;

use Core\DAL\Models\Role;
use Illuminate\Console\Command;

/**
 * Class CreateRoles.
 *
 * @package App\Console\Commands
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
     * @return mixed
     */
    public function handle()
    {
        Role::truncate();
        Role::insert([
            ['name' => Role::NAME_ADMINISTRATOR],
            ['name' => Role::NAME_CUSTOMER],
        ]);
        $this->comment('Roles were created.');
    }
}
