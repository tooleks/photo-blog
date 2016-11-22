<?php

namespace App\Console\Commands;

use App\Models\DB\Photo;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class CleanupPhotoFileSystem
 * @property Photo photoModel
 * @property Filesystem fs
 * @package App\Console\Commands
 */
class CleanupPhotoFileSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:photo_file_system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old photo directories from photos storage directory.';

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
        $directoriesToRemove = array_diff($this->getAllDirectories(), $this->getPhotoDirectories());

        array_map(function ($directory) {
            $this->comment($directory);
            $this->fs->deleteDirectory($directory);
        }, $directoriesToRemove);
    }

    /**
     * Get all directories.
     *
     * @return array
     */
    private function getAllDirectories()
    {
        return $this->fs->directories(config('main.path.photos'));
    }

    /**
     * Get photo directories.
     *
     * @return array
     */
    private function getPhotoDirectories()
    {
        $directories = [];

        $this->photoModel->chunk(500, function ($photos) use (&$directories) {
            foreach ($photos as $photo) {
                $directory = pathinfo($photo->path, PATHINFO_DIRNAME);
                if ($directory) {
                    array_push($directories, $directory);
                }
            }
        });

        return $directories;
    }
}
