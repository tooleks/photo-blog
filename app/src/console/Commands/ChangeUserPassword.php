<?php

namespace Console\Commands;

use App\Managers\User\ARUserManager;
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
    protected $signature = 'change:user-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user password';

    /**
     * @var ARUserManager
     */
    private $userManager;

    /**
     * Create a new command instance.
     *
     * @param ARUserManager $userManager
     */
    public function __construct(ARUserManager $userManager)
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

        $this->userManager->updateById($user->getId(), ['password' => $password]);

        $this->info('Password has been successfully changed.');
    }
}
