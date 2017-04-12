<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * Class SaveUploadedPhotoFile.
 *
 * @property Filesystem fileSystem
 * @package Api\V1\Http\Middleware
 */
class SaveUploadedPhotoFile
{
    use ValidatesRequests;

    /**
     * UploadPhotoFile constructor.
     *
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
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
            'file' => [
                'required',
                'filled',
                'file',
                'image',
                'mimes:jpeg,png',
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

        $filePath = $request->file('file')->store($directoryPath);

        if ($filePath === false) {
            throw new Exception(sprintf('File "%s" saving error.', $filePath));
        }

        $request->merge(['path' => $filePath, 'relative_url' => $this->fileSystem->url($filePath)]);

        return $next($request);
    }
}
