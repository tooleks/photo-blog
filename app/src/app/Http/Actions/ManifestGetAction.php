<?php

namespace App\Http\Actions;

use App\Services\Manifest\Contracts\Manifest;
use Illuminate\Routing\ResponseFactory;

/**
 * Class ManifestGetAction.
 *
 * @package App\Http\Actions
 */
class ManifestGetAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Manifest
     */
    private $manifest;

    /**
     * ManifestGetAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param Manifest $manifest
     */
    public function __construct(ResponseFactory $responseFactory, Manifest $manifest)
    {
        $this->responseFactory = $responseFactory;
        $this->manifest = $manifest;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $manifest = $this->manifest->get();

        return $this->responseFactory->json($manifest);
    }
}
