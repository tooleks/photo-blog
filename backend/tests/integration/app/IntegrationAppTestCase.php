<?php

/**
 * Class IntegrationAppTestCase.
 */
abstract class IntegrationAppTestCase extends TestCase
{
    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        $app = require $this->baseTestsDir . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
