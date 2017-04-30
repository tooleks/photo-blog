<?php

namespace Api\V1\Http\Middleware\Photos;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Storage;

/**
 * Class DeleteDirectory.
 *
 * @property Storage storage
 * @package Api\V1\Http\Middleware\Photos
 */
class DeleteDirectory
{
    /**
     * DeleteDirectory constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $directoryPath = $request->photo->directory_path ?? $request->published_photo->directory_path ?? null;
            if ($directoryPath) {
                $this->storage->deleteDirectory($directoryPath);
            }
        }

        return $response;
    }
}
