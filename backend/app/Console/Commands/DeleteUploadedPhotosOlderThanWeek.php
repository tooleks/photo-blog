<?php

namespace App\Console\Commands;

use App\Models\DB\Photo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class DeleteUploadedPhotosOlderThanWeek
 * @property Photo photoModel
 * @property Filesystem fs
 * @package App\Console\Commands
 */
class DeleteUploadedPhotosOlderThanWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:uploaded_photos_older_than_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete uploaded photos older than week.';

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
        $this->photoModel
            ->withTags()
            ->withThumbnails()
            ->wherePublished(false)
            ->where('updated_at', '<', (new Carbon())->addWeek('-1'))
            ->chunk(500, function ($photos) {
                /** @var Photo $photo */
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

        if ($this->fs->deleteDirectory($photo->directory_path)) {
            $this->comment(sprintf('Directory was deleted: "%s".', $photo->directory_path));
        }
    }
}
