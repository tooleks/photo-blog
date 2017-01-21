<?php


abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
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

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->artisan('migrate:rollback');
    }
}
