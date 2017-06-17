<?php

namespace Console\Commands;

use Core\DataProviders\Photo\PhotoDataProvider;
use Core\Models\Photo;
use Core\Services\Photo\ThumbnailsGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;

/**
 * Class GeneratePhotoThumbnails.
 *
 * @property Storage storage
 * @property ThumbnailsGeneratorService thumbnailsGenerator
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
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(
        Storage $storage,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        PhotoDataProvider $photoDataProvider
    )
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
            $this->comment("Generating photo [id:{$photo->id}] thumbnails ...");
            $this->generatePhotoThumbnails($photo);
        });
    }

    /**
     * Generate photo thumbnails.
     *
     * @param Photo $photo
     */
    public function generatePhotoThumbnails(Photo $photo)
    {
        $thumbnails = $this->thumbnailsGenerator->run($photo);

        $this->photoDataProvider->save($photo, compact('thumbnails'));
    }
}
