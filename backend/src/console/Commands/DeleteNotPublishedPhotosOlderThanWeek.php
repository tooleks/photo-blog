<?php

namespace Console\Commands;

use Closure;
use Core\Models\Photo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DeleteNotPublishedPhotosOlderThanWeek.
 *
 * @property Storage storage
 * @package Console\Commands
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
     * DeleteNotPublishedPhotosOlderThanWeek constructor.
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
        $this->eachNotPublishedPhotoOlderThanWeek(function (Photo $photo) {
            $this->comment(sprintf('Deleting photo (ID:%s) ...', $photo->id));
            $this->deletePhotoWithDirectory($photo);
        });
    }

    /**
     * Apply callback function on each not published photo older than week.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachNotPublishedPhotoOlderThanWeek(Closure $callback)
    {
        Photo::with('tags')
            ->with('thumbnails')
            ->whereIsPublished(false)
            ->where('updated_at', '<', (new Carbon())->addWeek('-1'))
            ->chunk(100, function (Collection $photos) use ($callback) {
                $photos->each($callback);
            });
    }

    /**
     * Delete photo with directory.
     *
     * @param Photo $photo
     * @return void
     */
    private function deletePhotoWithDirectory(Photo $photo)
    {
        if ($photo->delete()) {
            $this->comment(sprintf('Photo was deleted (ID:%s).', $photo->id));
        }

        if ($this->storage->disk('public')->deleteDirectory($photo->directory_path)) {
            $this->comment(sprintf('Photo directory was deleted (path:%s).', $photo->directory_path));
        }
    }
}
