<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\Request;
use Lib\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GenerateAvgColor.
 *
 * @property Storage storage
 * @property AvgColorPicker avgColorPicker
 * @package Api\V1\Http\Middleware
 */
class GenerateAvgColor
{
    /**
     * GenerateAvgColor constructor.
     *
     * @param Storage $storage
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(Storage $storage, AvgColorPicker $avgColorPicker)
    {
        $this->storage = $storage;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $imageAbsPath = $this->storage
                ->getDriver()
                ->getAdapter()
                ->getPathPrefix() . $request->get('thumbnails')[1]['path'];

        $request->merge(['avg_color' => $this->avgColorPicker->getImageAvgHexColorByPath($imageAbsPath)]);

        return $next($request);
    }
}
