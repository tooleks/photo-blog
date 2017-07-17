<?php

namespace Api\V1\Http\Proxy\Contracts;

/**
 * Interface AuthorizationProxy.
 *
 * @package Api\V1\Http\Proxy\Contracts
 */
interface AuthorizationProxy
{
    /**
     * Proxy authorize request with credentials.
     *
     * @param string $clientId
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function authorizeWithCredentials(string $clientId, string $username, string $password);

    /**
     * Proxy authorize request with refresh token.
     *
     * @param string $clientId
     * @param string $refreshToken
     * @return mixed
     */
    public function authorizeWithRefreshToken(string $clientId, string $refreshToken);
}
