<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase.
 */
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, FakeGenerator, ModelCreator;

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        putenv('DB_DEFAULT=testing');
        putenv('GOOGLE_RECAPTCHA_SECRET_KEY=');

        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('create:roles');
    }
}
