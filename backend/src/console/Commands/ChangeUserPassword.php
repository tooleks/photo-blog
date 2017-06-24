<?php

namespace Console\Commands;

use Core\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $name = $this->ask('Enter user\'s name to change password:');

        $user = User::whereName($name)->first();

        if (is_null($user)) {
            throw new ModelNotFoundException('User not found.');
        }

        $user->password = $this->hasher->make($this->ask('Enter new user\'s password:'));

        $user->saveOrFail();
    }
}
