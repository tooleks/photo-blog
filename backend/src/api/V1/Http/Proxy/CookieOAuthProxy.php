<?php

namespace Api\V1\Http\Proxy;

use Api\V1\Http\Proxy\Contracts\OAuthProxy as OAuthProxyContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use Laravel\Passport\ClientRepository as OAuthClientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            'client_secret' => $this->oAuthClientRepository->findActive($clientId)->secret ?? null,
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
            'client_secret' => $this->oAuthClientRepository->findActive($clientId)->secret ?? null,
            'scope' => '*',
            'refresh_token' => $refreshToken,
        ]);

        $response = $this->app->handle($proxy);

        return $response->isSuccessful()
            ? $this->proxyOnSuccessResponse($response)
            : $this->proxyOnErrorResponse($response);
    }

    /**
     * On success response callback.
     *
     * @param Response $response
     * @return Response
     */
    private function proxyOnSuccessResponse($response)
    {
        $responseContent = $this->decodeResponseContent($response->getContent());

        $proxyResponse = $this->responseFactory->json((object) []);

        foreach ($responseContent as $name => $value) {
            $proxyResponse->headers->setCookie(
                $this->cookieBakery->make(
                    $name,
                    $value,
                    60 * 24 * 30, // Thirty days in minutes.
                    null,
                    null,
                    isset($_SERVER['HTTPS']), // TODO: Fix dependency from the global $_SERVER variable.
                    true
                )
            );
        }

        return $proxyResponse;
    }

    /**
     * On error response callback.
     *
     * @param Response $response
     * @throws NotFoundHttpException
     * @throws AuthenticationException
     */
    private function proxyOnErrorResponse($response)
    {
        $responseContent = $this->decodeResponseContent($response->getContent());

        if (isset($responseContent['error']) && $responseContent['error'] === 'invalid_credentials') {
            throw new NotFoundHttpException(trans('auth.failed'));
        }

        throw new AuthenticationException;
    }

    /**
     * Decode a response content string.
     *
     * @param string $content
     * @return mixed
     */
    private function decodeResponseContent(string $content)
    {
        return \GuzzleHttp\json_decode($content, true);
    }
}
