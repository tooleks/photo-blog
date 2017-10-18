<?php

namespace Console\Commands;

use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use App\Services\Photo\Contracts\ThumbnailsGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;

/**
 * Class GeneratePhotoThumbnails.
 *
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
     * @var Storage
     */
    private $storage;

    /**
     * @var ThumbnailsGeneratorService
     */
    private $thumbnailsGenerator;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * GeneratePhotoThumbnails constructor.
     *
     * @param Storage $storage
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param PhotoManager $photoManager
     */
    public function __construct(
        Storage $storage,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        PhotoManager $photoManager
    )
    {
        parent::__construct();

        $this->storage = $storage;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->photoManager = $photoManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->photoManager->each(function (Photo $photo) {
            $this->comment("Generating photo {$photo->id} thumbnails.");
            $this->photoManager->generateThumbnails($photo);
        });
    }
}
