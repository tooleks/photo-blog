<?php

namespace Api\V1\Http\Middleware;

use App\Core\ThumbnailsGenerator\Contracts\ThumbnailsGeneratorContract;
use App\Core\Validator\Validator;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use Closure;

/**
 * Class CreateThumbnailFiles.
 *
 * @property Filesystem fs
 * @property ThumbnailsGeneratorContract thumbnailsGenerator
 * @package Api\V1\Http\Middleware
 */
class CreateThumbnailFiles
{
    use Validator;

    /**
     * CreateThumbnailFiles constructor.
     *
     * @param Filesystem $fs
     * @param ThumbnailsGeneratorContract $thumbnailsGenerator
     */
    public function __construct(Filesystem $fs, ThumbnailsGeneratorContract $thumbnailsGenerator)
    {
        $this->fs = $fs;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            'default' => [
                'path' => [
                    'required',
                    'filled',
                    'string',
                ],
                'relative_url' => [
                    'required',
                    'filled',
                    'string',
                ],
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
     * @throws FileNotFoundException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->validate($request->all(), 'default');

        $request->merge(['thumbnails' => $this->thumbnailsGenerator->generateThumbnails($request->get('path'))]);

        return $next($request);
    }
}
