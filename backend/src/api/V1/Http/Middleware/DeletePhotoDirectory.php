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
//            $this->fileSystem->deleteDirectory($request->photo->directory_path);
        }

        return $response;
    }
}
