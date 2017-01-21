<?php

namespace App\Console\Commands;

use App\Models\DB\Role;
use App\Models\DB\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class CreateAdministratorUser
 *
 * @property Hasher hasher
 * @property User userModel
 * @package App\Console\Commands
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
     * Create a new command instance.
     *
     * @param Hasher $hasher
     * @param User $userModel
     */
    public function __construct(Hasher $hasher, User $userModel)
    {
        parent::__construct();

        $this->hasher = $hasher;
        $this->userModel = $userModel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->userModel->newInstance();

        $user->name = $this->ask('Enter user\'s name:');
        $user->email = $this->ask('Enter user\'s email:');
        $user->password = $this->hasher->make($this->ask('Enter user\'s password:'));
        $user->api_token = $user->name;
        $user->setAdministratorRole();
        $user->saveOrFail();

        $this->comment(sprintf('Administrator user "%s" was created.', $user->name));
    }
}
