<?php

namespace Console\Commands;

use Core\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class CreateAdministratorUser.
 *
 * @package Console\Commands
 */
class CreateAdministratorUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:administrator_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create administrator user';

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * Create a new command instance.
     *
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        parent::__construct();

        $this->hasher = $hasher;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $user = new User;

        $user->name = $this->ask('Enter user\'s name:');
        $user->email = $this->ask('Enter user\'s email:');
        $user->password = $this->hasher->make($this->ask('Enter user\'s password:'));
        $user->generateApiToken();
        $user->setAdministratorRoleId();

        $user->saveOrFail();
    }
}
