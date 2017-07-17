<?php

namespace Api\V1\Http\Proxy;

use Api\V1\Http\Proxy\Contracts\AuthorizationProxy as AuthorizationProxyContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use Laravel\Passport\ClientRepository as OAuthClientRepository;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class CookieAuthorizationProxy.
 *
 * @package Api\V1\Http\Proxy
 */
class CookieAuthorizationProxy implements AuthorizationProxyContract
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var OAuthClientRepository
     */
    protected $oAuthClientRepository;

    /**
     * CookieAuthorizationProxy constructor.
     *
     * @param Application $app
     * @param ResponseFactory $responseFactory
     * @param OAuthClientRepository $oAuthClientRepository
     */
    public function __construct(Application $app, ResponseFactory $responseFactory, OAuthClientRepository $oAuthClientRepository)
    {
        $this->app = $app;
        $this->responseFactory = $responseFactory;
        $this->oAuthClientRepository = $oAuthClientRepository;
    }

    /**
     * @inheritdoc
     */
    public function authorizeWithCredentials(string $clientId, string $username, string $password)
    {
        $proxy = Request::create('oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => $clientId,
            'client_secret' => $this->oAuthClientRepository->findActive($clientId)->secret,
            'scope' => '*',
            'username' => $username,
            'password' => $password,
        ]);

        $response = $this->app->handle($proxy);

        return $response->isSuccessful()
            ? $this->proxyOnSuccessResponse($response)
            : $this->proxyOnErrorResponse($response);
    }

    /**
     * @inheritdoc
     */
    public function authorizeWithRefreshToken(string $clientId, string $refreshToken)
    {
        $proxy = Request::create('oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'client_id' => $clientId,
            'client_secret' => $this->oAuthClientRepository->findActive($clientId)->secret,
            'scope' => '*',
            'refresh_token' => $refreshToken,
        ]);

        $response = $this->app->handle($proxy);

        return $response->isSuccessful()
            ? $this->proxyOnSuccessResponse($response)
            : $this->proxyOnErrorResponse($response);
    }

    /**
     * Proxy success authorization response.
     *
     * @param mixed $originalResponse
     * @return Response
     */
    protected function proxyOnSuccessResponse($originalResponse): Response
    {
        $originalResponseContent = json_decode((string) $originalResponse->getContent(), true);

        $originalResponse = $this->responseFactory->make(null, Response::HTTP_CREATED);

        foreach ($originalResponseContent as $name => $value) {
            $originalResponse->withCookie(new Cookie($name, $value, 0, null, null, isset($_SERVER['HTTPS']), true));
        }

        return $originalResponse;
    }

    /**
     * @param mixed $originalResponse
     * @return mixed
     */
    protected function proxyOnErrorResponse($originalResponse)
    {
        return $originalResponse;
    }
}
