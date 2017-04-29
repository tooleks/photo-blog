<?php

namespace Console\Commands;

use Closure;
use Core\Models\Photo;
use Core\Models\Thumbnail;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\Eloquent\Collection;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GeneratePhotoThumbnails.
 *
 * @property Storage storage
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Console\Commands
 */
class GeneratePhotoThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:photo_thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate photo thumbnails';

    /**
     * GeneratePhotoThumbnails constructor.
     *
     * @param Storage $storage
     * @param ThumbnailsGenerator $thumbnailsGenerator
     */
    public function __construct(Storage $storage, ThumbnailsGenerator $thumbnailsGenerator)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->eachPhoto(function (Photo $photo) {
            $this->comment(sprintf('Generating thumbnails for photo (ID:%s) ...', $photo->id));
            $this->deletePhotoThumbnails($photo);
            $this->generatePhotoThumbnails($photo);
        });
    }

    /**
     * Apply callback function on each photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachPhoto(Closure $callback)
    {
        Photo::with('thumbnails')->chunk(100, function (Collection $photos) use ($callback) {
            $photos->each($callback);
        });
    }

    /**
     * Delete photo thumbnails
     *
     * @param Photo $photo
     */
    public function deletePhotoThumbnails(Photo $photo)
    {
        $photo->thumbnails->each(function (Thumbnail $thumbnail) use ($photo) {
            $photo->thumbnails()->detach($thumbnail->id);
            $thumbnail->delete();
            $this->storage->disk('public')->delete($thumbnail->path);
        });
    }

    /**
     * Generate photo thumbnails.
     *
     * @param Photo $photo
     */
    public function generatePhotoThumbnails(Photo $photo)
    {
        $storageAbsolutePath = $this->storage->disk('public')->getDriver()->getAdapter()->getPathPrefix();

        $absolutePhotoFilePath = $storageAbsolutePath . $photo->path;

        $metaData = $this->thumbnailsGenerator->generateThumbnails($absolutePhotoFilePath);

        foreach ($metaData as $metaDataItem) {
            $relativeThumbnailPath = str_replace($storageAbsolutePath, '', $metaDataItem['path']);
            $thumbnails[] = [
                'path' => $relativeThumbnailPath,
                'relative_url' => $this->storage->disk('public')->url($relativeThumbnailPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $photo->thumbnails()->createMany($thumbnails ?? []);
    }
}
