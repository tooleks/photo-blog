<?php

namespace Api\V1\Http\Middleware;

use App\Models\DB\Photo;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Closure;

/**
 * Class PhotoDirectoryCleaner
 * @property Filesystem $fs
 * @package App\Http\Middleware
 */
class PhotoDirectoryCleaner
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
            $photo = $request->uploadedPhoto->getOriginalEntity();
        } elseif (isset($request->photo)) {
            $photo = $request->photo->getOriginalEntity();
        } else {
            return $next($request);
        }

        $response = $next($request);

        if ($response->status() === 200) {
            $this->fs->deleteDirectory(pathinfo($photo->path, PATHINFO_DIRNAME));
        }

        return $response;
    }
}
