<?php

namespace Console\Commands;

use Core\Managers\User\Contracts\UserManager;
use Illuminate\Console\Command;

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
     * @var UserManager
     */
    private $userManager;

    /**
     * CreateAdministratorUser constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        parent::__construct();

        $this->userManager = $userManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->ask('Enter user\'s name:');
        $email = $this->ask('Enter user\'s email:');
        $password = $this->ask('Enter user\'s password:');

        $this->userManager->createAdministrator(compact('name', 'email', 'password'));
    }
}
