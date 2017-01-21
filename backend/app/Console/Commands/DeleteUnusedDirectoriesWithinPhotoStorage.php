<?php

namespace App\Console\Commands;

use App\Models\DB\Photo;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DeleteUnusedDirectoriesWithinPhotoStorage
 *
 * @property Photo photoModel
 * @property Filesystem fs
 * @package App\Console\Commands
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
     * Create a new command instance.
     *
     * @param Photo $photoModel
     * @param Filesystem $fs
     */
    public function __construct(Photo $photoModel, Filesystem $fs)
    {
        parent::__construct();

        $this->photoModel = $photoModel;
        $this->fs = $fs;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->getDirectoriesToDelete() as $directory) {
            if ($this->fs->deleteDirectory($directory)) {
                $this->comment(sprintf('Directory was deleted: "%s".', $directory));
            }
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
        return $this->fs->directories(config('main.storage.photos'));
    }

    /**
     * Get all of the directories used by photo models.
     *
     * @return array
     */
    private function getDirectoriesUsedByPhotoModels()
    {
        $this->photoModel->chunk(500, function (Collection $photos) use (&$directories) {
            /** @var Photo $photo */
            foreach ($photos as $photo) {
                $directories[] = $photo->directory_path;
            }
        });

        return $directories ?? [];
    }
}
