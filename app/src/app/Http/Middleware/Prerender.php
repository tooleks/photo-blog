<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class Prerender.
 *
 * @package App\Http\Middleware
 */
class Prerender
{
    private const PARAMETER_NAME = '_escaped_fragment_';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $allowedUserAgents;

    /**
     * @var array
     */
    private $whitelist;

    /**
     * @var array
     */
    private $blacklist;

    /**
     * Prerender constructor.
     *
     * @param HttpClient $httpClient
     * @param Config $config
     */
    public function __construct(HttpClient $httpClient, Config $config, ResponseFactory $responseFactory)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->responseFactory = $responseFactory;

        $this->enabled = $this->config->get('prerender.enabled');
        $this->url = $this->config->get('prerender.url');
        $this->token = $this->config->get('prerender.token');
        $this->allowedUserAgents = $this->config->get('prerender.allowed_user_agents');
        $this->whitelist = $this->config->get('prerender.whitelist');
        $this->blacklist = $this->config->get('prerender.blacklist');
    }

    /**
     * @param IlluminateRequest $request
     * @param Closure $next
     * @return mixed|SymfonyResponse
     * @throws GuzzleException
     */
    public function handle(IlluminateRequest $request, Closure $next)
    {
        if ($this->enabled && $this->shouldBePrerendered($request)) {
            $response = $this->prerender($request);
            if (!is_null($response)) {
                return $response;
            }
        }

        return $next($request);
    }

    /**
     * Determine if a request should be prerendered.
     *
     * @param IlluminateRequest $request
     * @return bool
     */
    private function shouldBePrerendered(IlluminateRequest $request): bool
    {
        $userAgent = $request->header('user-agent');
        $bufferAgent = $request->header('x-bufferbot');
        $referer = $request->header('referer');

        $isCrawler = false;

        if ($userAgent && $request->isMethod('GET')) {
            if ($request->has(static::PARAMETER_NAME)) {
                $isCrawler = true;
            }
            foreach ($this->allowedUserAgents as $crawlerUserAgent) {
                if (Str::contains(Str::lower($userAgent), Str::lower($crawlerUserAgent))) {
                    $isCrawler = true;
                }
            }
            if ($bufferAgent) {
                $isCrawler = true;
            }
        }

        if (!$isCrawler) {
            return false;
        }

        if ($this->whitelist) {
            if (!$this->inList($this->whitelist, $request->getRequestUri())) {
                return false;
            }
        }

        if ($this->blacklist) {
            $uris[] = $request->getPathInfo();
            if ($referer) {
                $uris[] = $referer;
            }
            if ($this->inList($this->blacklist, $uris)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prerender a requested resource.
     *
     * @param IlluminateRequest $request
     * @return null|SymfonyResponse
     * @throws GuzzleException
     */
    private function prerender(IlluminateRequest $request)
    {
        $headers = [
            'User-Agent' => $request->header('User-Agent'),
        ];

        if ($this->token) {
            $headers['X-Prerender-Token'] = $this->token;
        }

        $requestedResourceUrl = $this->getRequestedResourceUrl($request);
        $renderingUrl = $this->getRenderingUrl($requestedResourceUrl);

        try {
            $response = $this->httpClient->request('GET', $renderingUrl, ['headers' => $headers]);
            return $this->buildApplicationResponse($response);
        } catch (RequestException $e) {
            if ($this->config->get('app.debug')) {
                throw $e;
            } else {
                return $this->buildApplicationResponse($e->getResponse());
            }
        }
    }

    /**
     * Get an URL of the requested resource.
     *
     * @param IlluminateRequest $request
     * @return string
     */
    private function getRequestedResourceUrl(IlluminateRequest $request): string
    {
        $url = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo();

        if ($request->except(static::PARAMETER_NAME)) {
            $url .= '?' . http_build_query($request->except(static::PARAMETER_NAME));
        }

        return $url;
    }

    /**
     * Get an URL to render the requested resource.
     *
     * @param string $requestedResourceUrl
     * @return string
     */
    private function getRenderingUrl(string $requestedResourceUrl = null): string
    {
        $url = $this->url;

        if (!is_null($requestedResourceUrl)) {
            $url .= '/' . urlencode($requestedResourceUrl);
        }

        return $url;
    }

    /**
     * Determine if values exist in the list.
     *
     * @param mixed $values
     * @param array $list
     * @return bool
     */
    private function inList(array $list, $values): bool
    {
        $values = is_array($values) ? $values : [$values];

        foreach ($list as $pattern) {
            foreach ($values as $needle) {
                if (Str::is($pattern, $needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Build application response from prerendered page response.
     *
     * @param PsrResponseInterface $response
     * @return SymfonyResponse
     */
    private function buildApplicationResponse(PsrResponseInterface $response): SymfonyResponse
    {
        if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
            return $this->responseFactory->redirectTo(
                $response->getHeaders()["Location"][0],
                $response->getStatusCode()
            );
        }

        return (new HttpFoundationFactory)->createResponse($response);
    }
}
