<?php

namespace App\Http\Middleware;

use function App\env_production;
use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CorsHeaders.
 *
 * @package App\Http\Middleware
 */
class CorsHeaders
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * CorsHeaders constructor.
     *
     * @param Config $config
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Config $config, ResponseFactory $responseFactory)
    {
        $this->config = $config;
        $this->responseFactory = $responseFactory;
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
        $this->assertOriginHeader($request);

        $response = $request->getMethod() === 'OPTIONS'
            ? $this->responseFactory->make()
            : $next($request);

        $this->addCorsHeaders($request, $response);

        return $response;
    }

    /**
     * Assert the origin header value.
     *
     * @param Request $request
     */
    private function assertOriginHeader($request): void
    {
        if (env_production() && !$this->isAllowedOrigin($request->header('origin'))) {
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Determine if the origin value is allowed.
     *
     * @param string|null $origin
     * @return bool
     */
    private function isAllowedOrigin(?string $origin): bool
    {
        $allowedOrigins = explode(',', $this->config->get('cors.headers.access-control-allow-origin'));

        return collect($allowedOrigins)->reduce(function (bool $isAllowedOrigin, string $allowedOrigin) use ($origin) {
            return $isAllowedOrigin || trim($allowedOrigin) === trim($origin);
        }, false);
    }

    /**
     * Add the CORS headers to the response.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    private function addCorsHeaders($request, $response): void
    {
        $response->headers->add([
            'access-control-allow-origin' => $request->header('origin'),
            'access-control-allow-methods' => $this->config->get('cors.headers.access-control-allow-methods'),
            'access-control-allow-headers' => $this->config->get('cors.headers.access-control-allow-headers'),
            'access-control-allow-credentials' => $this->config->get('cors.headers.access-control-allow-credentials'),
        ]);
    }
}
