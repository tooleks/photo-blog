<?php

/**
 * Class IntegrationApiV1TestCase.
 */
abstract class IntegrationApiV1TestCase extends TestCase
{
    protected $resourceBaseName = 'api/v1';

    protected function getResourceFullName(string $resourceName): string
    {
        return sprintf('/%s/%s', $this->resourceBaseName, $resourceName);
    }
}
