<?php

namespace Api\V1\Http\Middleware\Photos;

use Closure;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Class SaveUploadedFile.
 *
 * @property Storage storage
 * @package Api\V1\Http\Middleware\Photos
 */
class SaveUploadedFile
{
    use ValidatesRequests;

    /**
     * SaveUploadedFile constructor.
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
    public function validateRequest(Request $request)
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
     * @return mixed
     * @throws RuntimeException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->validateRequest($request);

        $fileDirectoryRelPath = config('main.storage.photos') . '/' . str_random(10);

        $fileRelPath = $this->storage->put($fileDirectoryRelPath, $request->file('file'));

        if ($fileRelPath === false) {
            throw new RuntimeException(sprintf('File "%s" saving error.', $fileRelPath));
        }

        $request->merge(['path' => $fileRelPath, 'relative_url' => $this->storage->url($fileRelPath)]);

        return $next($request);
    }
}
