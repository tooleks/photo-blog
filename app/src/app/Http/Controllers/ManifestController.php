<?php

namespace App\Http\Controllers;

use App\Services\Manifest\Contracts\ManifestService;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;

/**
 * Class ManifestController.
 *
 * @package App\Http\Controllers
 */
class ManifestController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var ManifestService
     */
    private $manifest;

    /**
     * ManifestController constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param ManifestService $manifest
     */
    public function __construct(ResponseFactory $responseFactory, ManifestService $manifest)
    {
        $this->responseFactory = $responseFactory;
        $this->manifest = $manifest;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function json()
    {
        $manifest = $this->manifest->get();

        return $this->responseFactory->json($manifest);
    }
}
