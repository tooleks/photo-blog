<?php

namespace Api\V1\Http\Proxy;

use Api\V1\Http\Proxy\Contracts\OAuthProxy as OAuthProxyContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use Laravel\Passport\ClientRepository as OAuthClientRepository;

/**
 * Class OAuthCookieAuthorizationProxy.
 *
 * @package Api\V1\Http\Proxy
 */
class CookieOAuthProxy implements OAuthProxyContract
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var CookieFactory
     */
    private $cookieBakery;

    /**
     * @var OAuthClientRepository
     */
    private $oAuthClientRepository;

    /**
     * OAuthCookieAuthorizationProxy constructor.
     *
     * @param Application $app
     * @param ResponseFactory $responseFactory
     * @param CookieFactory $cookieBakery
     * @param OAuthClientRepository $oAuthClientRepository
     */
    public function __construct(Application $app, ResponseFactory $responseFactory, CookieFactory $cookieBakery, OAuthClientRepository $oAuthClientRepository)
    {
        $this->app = $app;
        $this->responseFactory = $responseFactory;
        $this->cookieBakery = $cookieBakery;
        $this->oAuthClientRepository = $oAuthClientRepository;
    }

    /**
     * @inheritdoc
     */
    public function requestTokenByCredentials(?string $clientId, ?string $username, ?string $password)
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
    public function requestTokenByRefreshToken(?string $clientId, ?string $refreshToken)
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
     * @param Response $response
     * @return Response
     */
    private function proxyOnSuccessResponse($response)
    {
        $responseContent = \GuzzleHttp\json_decode((string) $response->getContent(), true);

        $proxyResponse = $this->responseFactory->json((object) [], Response::HTTP_CREATED);

        foreach ($responseContent as $name => $value) {
            // TODO: Fix dependency from the global variable $_SERVER.
            $proxyResponse->headers->setCookie($this->cookieBakery->make($name, $value, $thirtyDays = (60 * 24 * 30),
                null, null, isset($_SERVER['HTTPS']), true));
        }

        return $proxyResponse;
    }

    /**
     * @param mixed $originalResponse
     * @return mixed
     */
    private function proxyOnErrorResponse($originalResponse)
    {
        return $originalResponse;
    }
}
