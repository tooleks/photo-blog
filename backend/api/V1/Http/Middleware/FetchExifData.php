<?php

namespace Api\V1\Http\Middleware;

use App\Core\ExifFetcher\Contracts\ExifFetcherContract;
use Illuminate\Http\Request;
use Closure;

/**
 * Class FetchExifData.
 *
 * @property ExifFetcherContract exifFetcher
 * @package Api\V1\Http\Middleware
 */
class FetchExifData
{
    /**
     * FetchExifData constructor.
     *
     * @param ExifFetcherContract $exifFetcher
     */
    public function __construct(ExifFetcherContract $exifFetcher)
    {
        $this->exifFetcher = $exifFetcher;
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
        $exif = $this->exifFetcher->fetch($request->file('file')->getPathname());

        $exif['FileName'] = $request->file('file')->getClientOriginalName();

        $request->merge(['exif' => $exif]);

        return $next($request);
    }
}
