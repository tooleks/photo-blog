<?php

namespace Console\Commands;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\Models\Photo;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;

/**
 * Class DeleteUnusedDirectoriesWithinPhotoStorage.
 *
 * @property Config config
 * @property Storage storage
 * @property PhotoDataProvider photoDataProvider
 * @package Console\Commands
 */
class DeleteUnusedDirectoriesWithinPhotoStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unused_directories_within_photo_storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused directories within photo storage';

    /**
     * DeleteUnusedDirectoriesWithinPhotoStorage constructor.
     *
     * @param Config $config
     * @param Storage $storage
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Config $config, Storage $storage, PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

        $this->config = $config;
        $this->storage = $storage;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        array_map(function ($directory) {
            $this->comment("Deleting directory 'path:{$directory}' ...");
            $this->storage->deleteDirectory($directory);
            $this->comment("Directory 'path:{$directory}' was successfully deleted.");
        }, $this->getDirectoriesToDelete());
    }

    /**
     * Get all of the directories to delete.
     *
     * @return array
     */
    private function getDirectoriesToDelete(): array
    {
        $directories = array_diff($this->getDirectoriesWithinPhotoStorage(), $this->getDirectoriesUsedByPhotoModels());

        return array_filter(array_unique($directories), 'boolval');
    }

    /**
     * Get all of the directories within a photo storage.
     *
     * @return array
     */
    private function getDirectoriesWithinPhotoStorage(): array
    {
        return $this->storage->directories($this->config->get('main.storage.photos'));
    }

    /**
     * Get all of the directories used by photo models.
     *
     * @return array
     */
    private function getDirectoriesUsedByPhotoModels(): array
    {
        $this->photoDataProvider->each(function (Photo $photo) use (&$directories) {
            $directories[] = $photo->directory_path;
        });

        return $directories ?? [];
    }
}
