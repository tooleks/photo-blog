<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GenerateThumbnails.
 *
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Api\V1\Http\Middleware
 */
class GenerateThumbnails
{
    use ValidatesRequests;

    /**
     * GenerateThumbnails constructor.
     *
     * @param ThumbnailsGenerator $thumbnailsGenerator
     */
    public function __construct(ThumbnailsGenerator $thumbnailsGenerator)
    {
        $this->thumbnailsGenerator = $thumbnailsGenerator;
    }

    /**
     * Validate request.
     *
     * @param Request $request
     */
    public function validateRequest($request)
    {
        $this->validate($request, [
            'path' => ['required', 'filled', 'string'],
            'relative_url' => ['required', 'filled', 'string'],
        ]);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     * @throws FileNotFoundException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->validateRequest($request);

        $request->merge(['thumbnails' => $this->thumbnailsGenerator->generateThumbnails($request->get('path'))]);

        return $next($request);
    }
}
