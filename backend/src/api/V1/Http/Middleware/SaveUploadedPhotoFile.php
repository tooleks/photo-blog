<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * Class SaveUploadedPhotoFile.
 *
 * @property Storage storage
 * @package Api\V1\Http\Middleware
 */
class SaveUploadedPhotoFile
{
    use ValidatesRequests;

    /**
     * SaveUploadedPhotoFile constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Validate request.
     *
     * @param Request $request
     */
    public function validateRequest($request)
    {
        $this->validate($request, [
            'file' => [
                'required',
                'filled',
                'file',
                'image',
                'mimes:jpeg,png',
                sprintf('dimensions:min_width=%s,min_height=%s', config('main.upload.min-image-width'), config('main.upload.min-image-height')),
                sprintf('min:%s', config('main.upload.min-size')),
                sprintf('max:%s', config('main.upload.max-size')),
            ],
        ]);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->validateRequest($request);

        $photoDirectoryRelPath = sprintf('%s/%s', config('main.storage.photos'), str_random(10));

        $photoRelPath = $this->storage->disk('public')->put($photoDirectoryRelPath, $request->file('file'));

        if ($photoRelPath === false) {
            throw new Exception(sprintf('File "%s" saving error.', $photoRelPath));
        }

        $request->merge(['path' => $photoRelPath, 'relative_url' => $this->storage->disk('public')->url($photoRelPath)]);

        return $next($request);
    }
}
