<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class SaveUploadedPhotoFile.
 *
 * @package Api\V1\Http\Middleware
 */
class SaveUploadedPhotoFile
{
    use ValidatesRequests;

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

        $directoryPath = sprintf('%s/%s', config('main.storage.photos'), str_random(10));

        $filePath = Storage::disk(config('filesystems.default'))->put($directoryPath, $request->file('file'));

        if ($filePath === false) {
            throw new Exception(sprintf('File "%s" saving error.', $filePath));
        }

        $request->merge(['path' => $filePath, 'relative_url' => Storage::disk(config('filesystems.default'))->url($filePath)]);

        return $next($request);
    }
}
