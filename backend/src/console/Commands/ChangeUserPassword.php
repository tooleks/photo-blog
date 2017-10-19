<?php

namespace Console\Commands;

use App\Managers\User\Contracts\UserManager;
use Illuminate\Console\Command;

/**
 * Class ChangeUserPassword.
 *
 * @package Console\Commands
 */
class ChangeUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:user_password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user password';

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * Create a new command instance.
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
        $name = $this->ask('Enter user\'s name to change password:');

        $user = $this->userManager->getByName($name);

        $password = $this->secret('Enter a new user\'s password:');

        $this->userManager->save($user, compact('password'));
    }
}
