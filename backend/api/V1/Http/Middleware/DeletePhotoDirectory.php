<?php

namespace Api\V1\Http\Middleware;

use App\Models\DB\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Filesystem\Filesystem;
use Closure;

/**
 * Class DeletePhotoDirectory.
 *
 * @property Filesystem $fs
 * @package Api\V1\Http\Middleware
 */
class DeletePhotoDirectory
{
    /**
     * PhotoDirectoryCleaner constructor.
     *
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
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
        /** @var Photo $photo */

        if (isset($request->uploadedPhoto)) {
            $photo = $request->uploadedPhoto->getOriginalModel();
        } elseif (isset($request->photo)) {
            $photo = $request->photo->getOriginalModel();
        } else {
            return $next($request);
        }

        /** @var Response $response */

        $response = $next($request);

        if ($response->status() === 200) {
            $this->fs->deleteDirectory($photo->directory_path);
        }

        return $response;
    }
}
