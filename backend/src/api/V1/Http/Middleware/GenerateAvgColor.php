<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lib\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GenerateAvgColor.
 *
 * @property AvgColorPicker avgColorPicker
 * @package Api\V1\Http\Middleware
 */
class GenerateAvgColor
{
    /**
     * GenerateAvgColor constructor.
     *
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(AvgColorPicker $avgColorPicker)
    {
        $this->avgColorPicker = $avgColorPicker;
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
        $storageRootPath = env('APP_ENV') === 'testing'
            ? config('filesystems.disks.testing.root') . '/' . config('filesystems.default')
            : config('filesystems.disks.local.root');

        $thumbnailPath = $storageRootPath . '/' . $request->get('thumbnails')[1]['path'];

        $avgColor = $this->avgColorPicker->getImageAvgHexColorByPath($thumbnailPath);

        $request->merge(['avg_color' => $avgColor]);

        return $next($request);
    }
}
