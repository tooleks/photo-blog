<?php

namespace Api\V1\Http\Proxy;

use Api\V1\Http\Proxy\Contracts\OAuthProxy as OAuthProxyContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @var Request
     */
    private $request;

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
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param CookieFactory $cookieBakery
     * @param OAuthClientRepository $oAuthClientRepository
     */
    public function __construct(Application $app, Request $request, ResponseFactory $responseFactory, CookieFactory $cookieBakery, OAuthClientRepository $oAuthClientRepository)
    {
        $this->app = $app;
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->cookieBakery = $cookieBakery;
        $this->oAuthClientRepository = $oAuthClientRepository;
    }

    /**
     * @inheritdoc
     */
    public function requestTokenByCredentials(string $clientId, string $username, string $password)
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

        return $this->handleResponse($response);
    }

    /**
     * Handle proxy response.
     *
     * @param Response $response
     * @return Response
     */
    private function handleResponse($response)
    {
        if ($response->isSuccessful()) {
            return $this->proxyOnSuccessResponse($response);
        } else {
            return $this->proxyOnErrorResponse($response);
        }
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

        $proxyResponse = $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);

        foreach ($responseContent as $name => $value) {
            $proxyResponse->headers->setCookie(
                $this->cookieBakery->make(
                    $name,
                    $value,
                    60 * 24 * 30, // Thirty days in minutes.
                    null,
                    null,
                    $this->request->isSecure(),
                    $name !== 'expires_in'
                )
            );
        }

        return $proxyResponse;
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

        return $this->handleResponse($response);
    }
}
