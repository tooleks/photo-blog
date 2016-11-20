<?php

namespace App\Console\Commands;

use App\Models\DB\Role;
use Illuminate\Console\Command;

/**
 * Class MakeRoles
 * @property Role roleModel
 * @package App\Console\Commands
 */
class MakeRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make roles.';

    /**
     * Create a new command instance.
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
        $this->roleModel->create(['name' => Role::NAME_ADMINISTRATOR]);
        $this->roleModel->create(['name' => Role::NAME_CUSTOMER]);
        $this->comment('Roles were successfully created.');
    }
}
