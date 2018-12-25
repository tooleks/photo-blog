<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Arr;
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
    private const AJAX_CRAWLING_PARAM_NAME = '_escaped_fragment_';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Config
     */
    private $config;

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
     * @param ResponseFactory $responseFactory
     * @param Config $config
     */
    public function __construct(HttpClient $httpClient, ResponseFactory $responseFactory, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->responseFactory = $responseFactory;
        $this->config = $config;

        $this->enabled = $this->config->get('prerender.enabled');
        $this->url = $this->config->get('prerender.url');
        $this->token = $this->config->get('prerender.token', null);
        $this->allowedUserAgents = $this->config->get('prerender.allowed_user_agents', []);
        $this->whitelist = $this->config->get('prerender.whitelist', []);
        $this->blacklist = $this->config->get('prerender.blacklist', []);
    }

    /**
     * @param IlluminateRequest $request
     * @param Closure $next
     * @return SymfonyResponse
     * @throws GuzzleException
     */
    public function handle(IlluminateRequest $request, Closure $next)
    {
        $isSentByCrawler = $this->isSentByCrawler($request);
        $isWhitelisted = $this->isWhitelisted($request);
        $isBlacklisted = $this->isBlacklisted($request);

        if ($this->enabled && $isSentByCrawler && $isWhitelisted && !$isBlacklisted) {
            return $this->prerender($request);
        }

        return $next($request);
    }

    /**
     * Determine if a request is sent by crawler bot.
     *
     * @param IlluminateRequest $request
     * @return bool
     */
    private function isSentByCrawler(IlluminateRequest $request): bool
    {
        if ($request->isMethod('GET')) {
            $hasAjaxCrawlingParameter = $request->has(static::AJAX_CRAWLING_PARAM_NAME);
            $isAllowedUserAgent = $this->isAllowedUserAgent($request);
            $isBufferBot = $request->header('x-bufferbot');
            return $hasAjaxCrawlingParameter || $isAllowedUserAgent || $isBufferBot;
        }

        return false;
    }

    /**
     * Determine if a request user agent is listed in allowed user agents list.
     *
     * @param IlluminateRequest $request
     * @return bool
     */
    private function isAllowedUserAgent(IlluminateRequest $request): bool
    {
        $userAgent = $request->header('user-agent');

        if ($userAgent) {
            foreach ($this->allowedUserAgents as $allowedUserAgent) {
                if (Str::contains(Str::lower($userAgent), Str::lower($allowedUserAgent))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if request URI is in the white list.
     *
     * @param IlluminateRequest $request
     * @return bool
     */
    private function isWhitelisted(IlluminateRequest $request): bool
    {
        $path = $request->getPathInfo();

        if ($this->whitelist) {
            if (!$this->isListed($this->whitelist, $path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if values exist in the list.
     *
     * @param array $list
     * @param mixed $values
     * @return bool
     */
    private function isListed(array $list, $values): bool
    {
        foreach ($list as $pattern) {
            foreach (Arr::wrap($values) as $value) {
                if (Str::is($pattern, $value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if request URI is in the black list.
     *
     * @param IlluminateRequest $request
     * @return bool
     */
    private function isBlacklisted(IlluminateRequest $request): bool
    {
        $path = $request->getPathInfo();
        $referer = $request->header('referer');

        if ($this->blacklist) {
            $uris[] = $path;
            if ($referer) {
                $uris[] = $referer;
            }
            if ($this->isListed($this->blacklist, $uris)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prerender a requested resource.
     *
     * @param IlluminateRequest $request
     * @return SymfonyResponse
     * @throws GuzzleException
     */
    private function prerender(IlluminateRequest $request)
    {
        $headers = [
            'user-agent' => $request->header('user-agent'),
        ];

        if ($this->token) {
            $headers['x-prerender-token'] = $this->token;
        }

        $requestUrl = $this->getRequestUrl($request);

        try {
            $response = $this->httpClient->request('GET', $this->getRenderingUrl($requestUrl), ['headers' => $headers]);
            return $this->buildApplicationResponse($response);
        } catch (RequestException $e) {
            return $this->buildApplicationResponse($e->getResponse());
        }
    }

    /**
     * Get an URL of the resource from request.
     *
     * @param IlluminateRequest $request
     * @return string
     */
    private function getRequestUrl(IlluminateRequest $request): string
    {
        $url = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo();

        // Get parameters excluding AJAX crawling parameter.
        $params = $request->except(static::AJAX_CRAWLING_PARAM_NAME);

        if ($params) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Get an URL to render the requested resource.
     *
     * @param string $resourceUrl
     * @return string
     */
    private function getRenderingUrl(string $resourceUrl = null): string
    {
        $url = $this->url;

        if (!is_null($resourceUrl)) {
            $url .= '/' . urlencode($resourceUrl);
        }

        return $url;
    }

    /**
     * Build application response from prerendered page response.
     *
     * @param PsrResponseInterface $response
     * @return SymfonyResponse
     */
    private function buildApplicationResponse(PsrResponseInterface $response): SymfonyResponse
    {
        return (new HttpFoundationFactory)->createResponse($response);
    }
}
