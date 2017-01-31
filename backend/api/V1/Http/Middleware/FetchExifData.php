<?php

namespace Api\V1\Http\Middleware;

use App\Core\ExifFetcher\Contracts\ExifFetcherContract;
use App\Core\Validator\Validator;
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
    use Validator;

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
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            'default' => [
                'file' => ['required', 'filled', 'file', 'image'],
            ],
        ];
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
        $this->validate($request->all(), 'default');

        $exif = $this->exifFetcher->fetch($request->file('file')->getPathname());

        // Replace the temporary file name with the original one.
        $exif['FileName'] = $request->file('file')->getClientOriginalName();

        $request->merge(['exif' => ['data' => $exif]]);

        return $next($request);
    }
}
