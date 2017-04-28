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
     * @param array $attributes
     * @return User
     */
    protected function createTestAdministratorUser(array $attributes = [])
    {
        $requiredAttributes = ['role_id' => Role::administrator()->first()->id ?? null];

        $user = factory(User::class)->create(array_merge($attributes, $requiredAttributes));

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
