<?php

namespace Console\Commands;

use Core\DataProviders\Photo\PhotoDataProvider;
use Core\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GeneratePhotoThumbnails.
 *
 * @property Storage storage
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @property PhotoDataProvider photoDataProvider
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
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Storage $storage, ThumbnailsGenerator $thumbnailsGenerator, PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->photoDataProvider->each(function (Photo $photo) {
            $this->comment("Generating photo thumbnails (id:{$photo->id}) ...");
            $this->generatePhotoThumbnails($photo);
            $this->comment("Photo thumbnails was successfully generated (id:{$photo->id}).");
        }, 100, ['with' => ['thumbnails']]);
    }

    /**
     * Generate photo thumbnails.
     *
     * @param Photo $photo
     */
    public function generatePhotoThumbnails(Photo $photo)
    {
        $photoRelPath = $photo->path;
        $photoAbsPath = $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photoRelPath;

        if (!file_exists($photoAbsPath)) {
            $this->comment("File '{$photoAbsPath}' does not exists.");
            return;
        }

        $metaData = $this->thumbnailsGenerator->generateThumbnails($photoAbsPath);
        foreach ($metaData as $metaDataItem) {
            $thumbnailRelPath = pathinfo($photoRelPath, PATHINFO_DIRNAME) . '/' . pathinfo($metaDataItem['path'], PATHINFO_BASENAME);
            $thumbnails[] = [
                'path' => $thumbnailRelPath,
                'relative_url' => $this->storage->url($thumbnailRelPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $this->photoDataProvider->save($photo, ['thumbnails' => $thumbnails ?? []], ['with' => ['thumbnails']]);
    }
}
