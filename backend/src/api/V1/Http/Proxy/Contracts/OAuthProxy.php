<?php

namespace Api\V1\Http\Proxy\Contracts;

/**
 * Interface OAuthProxy.
 *
 * @package Api\V1\Http\Proxy\Contracts
 */
interface OAuthProxy
{
    /**
     * Request token by the refresh credentials.
     *
     * @param string $clientId
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function requestTokenByCredentials(string $clientId, string $username, string $password);
}
