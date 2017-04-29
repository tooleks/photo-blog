<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use League\Flysystem\FileNotFoundException;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GenerateThumbnails.
 *
 * @property Storage storage
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Api\V1\Http\Middleware
 */
class GenerateThumbnails
{
    use ValidatesRequests;

    /**
     * GenerateThumbnails constructor.
     *
     * @param Storage $storage
     * @param ThumbnailsGenerator $thumbnailsGenerator
     */
    public function __construct(Storage $storage, ThumbnailsGenerator $thumbnailsGenerator)
    {
        $this->storage = $storage;
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
            'path' => ['required', 'string'],
            'relative_url' => ['required', 'string'],
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

        $storageAbsPath = $this->storage->disk('public')->getDriver()->getAdapter()->getPathPrefix();

        $photoAbsPath = $storageAbsPath . $request->get('path');

        $metaData = $this->thumbnailsGenerator->generateThumbnails($photoAbsPath);

        foreach ($metaData as $metaDataItem) {
            $relativeThumbnailPath = str_replace($storageAbsPath, '', $metaDataItem['path']);
            $thumbnails[] = [
                'path' => $relativeThumbnailPath,
                'relative_url' => $this->storage->disk('public')->url($relativeThumbnailPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $request->merge(['thumbnails' => $thumbnails ?? []]);

        return $next($request);
    }
}
