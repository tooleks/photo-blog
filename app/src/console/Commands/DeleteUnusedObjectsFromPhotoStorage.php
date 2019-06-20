<?php

namespace Console\Commands;

use App\Models\Photo;
use App\Models\Thumbnail;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class DeleteUnusedObjectsFromPhotoStorage.
 *
 * @package Console\Commands
 */
class DeleteUnusedObjectsFromPhotoStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unused-objects-from-photo-storage
                                {--chunk_size=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused objects from photo storage';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * DeleteUnusedObjectsFromPhotoStorage constructor.
     *
     * @param Config $config
     * @param Storage $storage
     */
    public function __construct(Config $config, Storage $storage)
    {
        parent::__construct();

        $this->config = $config;
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->getDirectoriesToDelete() as $directory) {
            $this->comment("Deleting directory {$directory}...");
            $this->storage->deleteDirectory($directory);
        }

        foreach ($this->getFilesToDelete() as $file) {
            $this->comment("Deleting file {$file}...");
            $this->storage->delete($file);
        }
    }

    /**
     * Get directories to delete.
     *
     * @return array
     */
    protected function getDirectoriesToDelete(): array
    {
        $directories = array_diff($this->getAllDirectoriesFromStorage(), $this->getAllDirectoriesFromDataProvider());

        return array_filter(array_unique($directories), function ($directory) {
            return Str::length($directory) > 0;
        });
    }

    /**
     * Get all directories from a storage.
     *
     * @return array
     */
    protected function getAllDirectoriesFromStorage(): array
    {
        return array_values($this->storage->allDirectories('photos'));
    }

    /**
     * Get all directories from a data provider.
     *
     * @return array
     */
    protected function getAllDirectoriesFromDataProvider(): array
    {
        $directories = [];

        (new Photo)
            ->newQuery()
            ->chunk($this->option('chunk_size'), function (Collection $photos) use (&$directories) {
                $photos->each(function (Photo $photo) use (&$directories) {
                    $directories[] = pathinfo($photo->path, PATHINFO_DIRNAME);
                });
            });

        return array_values($directories);
    }

    /**
     * Get files to delete.
     *
     * @return array
     */
    protected function getFilesToDelete(): array
    {
        $files = array_diff($this->getAllFilesFromStorage(), $this->getAllFilesFromDataProvider());

        return array_filter(array_unique($files), function ($directory) {
            return Str::length($directory) > 0;
        });
    }

    /**
     * Get all files from a storage.
     *
     * @return array
     */
    protected function getAllFilesFromStorage(): array
    {
        return array_values($this->storage->allFiles('photos'));
    }

    /**
     * Get all files from a data provider.
     *
     * @return array
     */
    protected function getAllFilesFromDataProvider(): array
    {
        $files = [];

        (new Photo)
            ->newQuery()
            ->chunk($this->option('chunk_size'), function (Collection $photos) use (&$files) {
                $photos->each(function (Photo $photo) use (&$files) {
                    $files[] = $photo->path;
                    $photo->thumbnails->each(function (Thumbnail $thumbnail) use (&$files) {
                        $files[] = $thumbnail->path;
                    });
                });
            });

        return array_values($files);
    }
}
