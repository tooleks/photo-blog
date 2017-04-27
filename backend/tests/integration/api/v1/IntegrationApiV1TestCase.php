<?php

/**
 * Class IntegrationApiV1TestCase.
 */
abstract class IntegrationApiV1TestCase extends TestCase
{
    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        $app = require $this->baseTestsDir . '/../bootstrap/api.v1.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
