<?php

namespace Api\V1\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Storage;

/**
 * Class DeletePhotoDirectory.
 *
 * @package Api\V1\Http\Middleware
 */
class DeletePhotoDirectory
{
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

        if ($response->isSuccessful()) {
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
            Storage::disk(config('filesystems.default'))->deleteDirectory($directoryPath);
        }
    }
}
