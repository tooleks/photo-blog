<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use League\Flysystem\FileNotFoundException;

/**
 * Class UploadPhotoFile.
 *
 * @property Filesystem fileSystem
 * @package Api\V1\Http\Middleware
 */
class UploadPhotoFile
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
                'min:' . config('main.upload.min-size'),
                'max:' . config('main.upload.max-size'),
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
     * @throws FileNotFoundException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->validateRequest($request);

        $photoFilePath = $request->file('file')->store($this->generateDirPath());

        if ($photoFilePath === false) {
            throw new FileNotFoundException("File '{$photoFilePath}' saving error.");
        }

        $request->merge(['path' => $photoFilePath, 'relative_url' => $this->fileSystem->url($photoFilePath)]);

        return $next($request);
    }

    /**
     * Generate directory path for photo.
     *
     * @return string
     */
    protected function generateDirPath()
    {
        return sprintf('%s/%s', config('main.storage.photos'), str_random(10));
    }
}
