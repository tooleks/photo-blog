<?php

namespace App\Console\Commands;

use Core\DAL\Models\Role;
use Illuminate\Console\Command;

/**
 * Class CreateRoles.
 *
 * @property Role roleModel
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
     * Create a new command instance.
     *
     * @param Role $roleModel
     */
    public function __construct(Role $roleModel)
    {
        parent::__construct();

        $this->roleModel = $roleModel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->roleModel->truncate();
        $this->roleModel->insert([
            ['name' => Role::NAME_ADMINISTRATOR],
            ['name' => Role::NAME_CUSTOMER],
        ]);
        $this->comment('Roles were created.');
    }
}
