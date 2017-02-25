<?php

namespace Api\V1\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Closure;

/**
 * Class DeletePhotoDirectory.
 *
 * @property Filesystem $fileSystem
 * @package Api\V1\Http\Middleware
 */
class DeletePhotoDirectory
{
    /**
     * DeletePhotoDirectory constructor.
     *
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);

        if ($response->status() === 200) {
            $this->deletePhotoDirectory($request->photo->directory_path ?? $request->published_photo->directory_path ?? null);
        }

        return $response;
    }

    /**
     * Delete photo directory.
     *
     * @param string|null $directoryPath
     */
    public function deletePhotoDirectory($directoryPath)
    {
        if (!is_null($directoryPath)) {
            $this->fileSystem->deleteDirectory($directoryPath);
        }
    }
}
