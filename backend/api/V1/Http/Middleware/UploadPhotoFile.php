<?php

namespace Api\V1\Http\Middleware;

use App\Core\Validator\Validator;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use Closure;

/**
 * Class UploadPhotoFile
 * @property Filesystem fs
 * @package Api\V1\Http\Middleware
 */
class UploadPhotoFile
{
    use Validator;

    /**
     * PhotoFileUploader constructor.
     *
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            'default' => [
                'file' => [
                    'required',
                    'filled',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                    'min:' . config('main.upload.min-size'),
                    'max:' . config('main.upload.max-size'),
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

        $photoFilePath = $request->file('file')->store($this->generateDirPath());

        if ($photoFilePath === false) {
            throw new FileNotFoundException("File '{$photoFilePath}' saving error.");
        }

        $request->merge(['path' => $photoFilePath, 'relative_url' => $this->fs->url($photoFilePath)]);

        return $next($request);
    }

    /**
     * Generate directory path for photo.
     *
     * @return string
     */
    protected function generateDirPath()
    {
        return sprintf('%s/%s', config('main.storage.photos'), str_random(10));
    }
}
