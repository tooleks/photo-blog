<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddCorsHeaders.
 *
 * @package Api\V1\Http\Middleware
 */
class AddCorsHeaders
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * AddCorsHeaders constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->addCorsHeaders($response);

        return $response;
    }

    /**
     * Add the CORS headers to the response.
     *
     * @param Response $response
     * @return void
     */
    protected function addCorsHeaders($response): void
    {
        $response->headers->add([
            'Access-Control-Allow-Origin' => $this->config->get('http.cors.allowed_origins'),
            'Access-Control-Allow-Methods' => $this->config->get('http.cors.allowed_methods'),
            'Access-Control-Allow-Headers' => $this->config->get('http.cors.allowed_headers'),
            'Access-Control-Allow-Credentials' => $this->config->get('http.cors.allowed_credentials'),
        ]);
    }
}
