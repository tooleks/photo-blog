<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class TestCase.
 */
abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base tests directory to use while testing the application.
     *
     * @var string
     */
    protected $baseTestsDir = __DIR__;

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        $app = require $this->baseTestsDir . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Get faker instance.
     *
     * @return Faker\Generator
     */
    protected function fake()
    {
        static $faker;

        $faker = $faker ?? $this->app->make(Faker\Generator::class);

        return $faker;
    }

    /**
     * Create user for testing scenarios.
     *
     * @param array $attributes
     * @return User
     */
    protected function createTestUser(array $attributes = [])
    {
        $defaultAttributes = ['role_id' => Role::whereNameAdministrator()->first()->id ?? null];

        if (array_key_exists('password', $attributes)) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $user = factory(User::class)->create(array_merge($defaultAttributes, $attributes));

        return $user;
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('create:roles');
    }

    protected function tearDown()
    {
        $this->artisan('migrate:rollback');

        parent::tearDown();
    }
}
