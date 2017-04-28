<?php

use Core\Models\Role;
use Core\Models\User;

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
     * Create administrator user for testing scenarios.
     *
     * @return User
     */
    protected function createTestAdministratorUser()
    {
        $user = factory(User::class)->create(['role_id' => Role::administrator()->first()->id ?? null]);

        return $user;
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

//        $this->artisan('migrate:rollback');
        $this->artisan('migrate');
        $this->artisan('create:roles');
    }

    protected function tearDown()
    {
        $this->artisan('migrate:rollback');
    }
}
