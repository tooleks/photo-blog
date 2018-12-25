<?php

namespace Api\V1\Http\Actions;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class InfoGetAction.
 *
 * @package Api\V1\Http\Actions
 */
class InfoGetAction
{
    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * InfoGetAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param Config $config
     */
    public function __construct(ResponseFactory $responseFactory, Config $config)
    {
        $this->responseFactory = $responseFactory;
        $this->config = $config;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke()
    {
        return $this->responseFactory->json([
            'name' => $this->config->get('app.name'),
            'version' => '1',
        ]);
    }
}
