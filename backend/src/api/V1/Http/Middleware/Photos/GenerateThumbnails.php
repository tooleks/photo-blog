<?php

namespace Api\V1\Http\Middleware\Photos;

use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GenerateThumbnails.
 *
 * @property Storage storage
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Api\V1\Http\Middleware\Photos
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
    public function validateRequest(Request $request)
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
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->validateRequest($request);

        $photoRelPath = $request->get('path');
        $photoAbsPath = $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photoRelPath;

        $metaData = $this->thumbnailsGenerator->generateThumbnails($photoAbsPath);
        foreach ($metaData as $metaDataItem) {
            $thumbnailRelPath = pathinfo($photoRelPath, PATHINFO_DIRNAME) . '/' . pathinfo($metaDataItem['path'], PATHINFO_BASENAME);
            $thumbnails[] = [
                'path' => $thumbnailRelPath,
                'relative_url' => $this->storage->url($thumbnailRelPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $request->merge(['thumbnails' => $thumbnails ?? []]);

        return $next($request);
    }
}
