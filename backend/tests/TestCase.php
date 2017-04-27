<?php

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
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:rollback');
        $this->artisan('migrate');
        $this->artisan('create:roles');
    }

    protected function tearDown()
    {
        $this->artisan('migrate:rollback');
    }
}
