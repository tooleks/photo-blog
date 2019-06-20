<?php

namespace Console\Commands;

use App\Managers\User\ARUserManager;
use App\Models\Role;
use Illuminate\Console\Command;
use function App\Util\url_frontend_sign_in;

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
    protected $signature = 'create:administrator-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create administrator user';

    /**
     * @var ARUserManager
     */
    private $userManager;

    /**
     * CreateAdministratorUser constructor.
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
        $this->userManager->create([
            'role_id' => (new Role)->newQuery()->whereNameAdministrator()->firstOrFail()->id,
            'name' => $this->ask('Enter user\'s name'),
            'email' => $this->ask('Enter user\'s email'),
            'password' => $this->secret('Enter user\'s password'),
        ]);

        $signInUrl = url_frontend_sign_in();

        $this->info("User has been successfully created. Now you can sign in into your account {$signInUrl}.");
    }
}
