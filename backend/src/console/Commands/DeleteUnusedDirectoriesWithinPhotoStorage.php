<?php

namespace Console\Commands;

use Core\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DeleteUnusedDirectoriesWithinPhotoStorage.
 *
 * @property Storage storage
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
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        parent::__construct();

        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->getDirectoriesToDelete() as $directory) {
            $this->comment(sprintf('Deleting directory (path:%s) ...', $directory));
            $this->storage->deleteDirectory($directory) && $this->comment(sprintf('Directory was deleted (path:%s).', $directory));
        }
    }

    /**
     * Get all of the directories to delete.
     *
     * @return array
     */
    private function getDirectoriesToDelete()
    {
        $directories = array_diff($this->getDirectoriesWithinPhotoStorage(), $this->getDirectoriesUsedByPhotoModels());

        return array_filter(array_unique($directories), function ($directory) {
            return (bool)$directory;
        });
    }

    /**
     * Get all of the directories within a photo storage.
     *
     * @return array
     */
    private function getDirectoriesWithinPhotoStorage()
    {
        return $this->storage->directories(config('main.storage.photos'));
    }

    /**
     * Get all of the directories used by photo models.
     *
     * @return array
     */
    private function getDirectoriesUsedByPhotoModels()
    {
        Photo::chunk(500, function (Collection $photos) use (&$directories) {
            foreach ($photos as $photo) {
                $directories[] = $photo->directory_path;
            }
        });

        return $directories ?? [];
    }
}
