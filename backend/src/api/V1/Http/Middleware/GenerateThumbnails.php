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
 * @property Filesystem fileSystem
 * @package Api\V1\Http\Middleware
 */
class GenerateThumbnails
{
    use ValidatesRequests;

    /**
     * GenerateThumbnails constructor.
     *
     * @param ThumbnailsGenerator $thumbnailsGenerator
     * @param Filesystem $fileSystem
     */
    public function __construct(ThumbnailsGenerator $thumbnailsGenerator, Filesystem $fileSystem)
    {
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->fileSystem = $fileSystem;
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

        $absolutePhotoFilePath = storage_path('app') . '/' . $request->get('path');

        $metaData = $this->thumbnailsGenerator->generateThumbnails($absolutePhotoFilePath);
        
        foreach ($metaData as $metaDataItem) {
            $relativeThumbnailPath = str_replace(storage_path('app') . '/', '', $metaDataItem['path']);
            $thumbnails[] = [
                'path' => $relativeThumbnailPath,
                'relative_url' => $this->fileSystem->url($relativeThumbnailPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $request->merge(['thumbnails' => $thumbnails ?? []]);

        return $next($request);
    }
}
