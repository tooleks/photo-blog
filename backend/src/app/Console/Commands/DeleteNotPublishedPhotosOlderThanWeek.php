<?php

namespace App\Console\Commands;

use Core\DAL\Models\Photo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class DeleteNotPublishedPhotosOlderThanWeek.
 *
 * @property Filesystem fileSystem
 * @package App\Console\Commands
 */
class DeleteNotPublishedPhotosOlderThanWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:not_published_photos_older_than_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete not published photos older than week';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
        parent::__construct();

        $this->fileSystem = $fileSystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Photo::with('tags')
            ->with('thumbnails')
            ->where('is_published', false)
            ->where('updated_at', '<', (new Carbon())->addWeek('-1'))
            ->chunk(500, function ($photos) {
                foreach ($photos as $photo) {
                    $this->deletePhotoWithDirectory($photo);
                }
            });
    }

    /**
     * Delete photo with directory.
     *
     * @param Photo $photo
     */
    private function deletePhotoWithDirectory(Photo $photo)
    {
        if ($photo->delete()) {
            $this->comment(sprintf('Photo was deleted: %s.', $photo->toJson()));
        }

        if (!$photo->directory_path) {
            return;
        }

        if ($this->fileSystem->deleteDirectory($photo->directory_path)) {
            $this->comment(sprintf('Directory was deleted: "%s".', $photo->directory_path));
        }
    }
}
