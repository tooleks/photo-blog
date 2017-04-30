<?php

namespace Api\V1\Http\Middleware\Photos;

use Closure;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\Request;
use Lib\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GenerateAvgColor.
 *
 * @property Storage storage
 * @property AvgColorPicker avgColorPicker
 * @package Api\V1\Http\Middleware\Photos
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
    public function handle(Request $request, Closure $next)
    {
        $storageAbsPath = $this->storage->getDriver()->getAdapter()->getPathPrefix();

        $fileAbsPath = $storageAbsPath . $request->get('thumbnails')[1]['path'];

        $request->merge(['avg_color' => $this->avgColorPicker->getImageAvgHexColorByPath($fileAbsPath)]);

        return $next($request);
    }
}
