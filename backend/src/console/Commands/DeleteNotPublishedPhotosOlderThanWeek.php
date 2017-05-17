<?php

namespace Console\Commands;

use Closure;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\Models\Photo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\DataProvider\Criterias\WhereUpdatedAtLessThan;

/**
 * Class DeleteNotPublishedPhotosOlderThanWeek.
 *
 * @property Storage storage
 * @property PhotoDataProvider photoDataProvider
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
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Storage $storage, PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

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
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->applyCriteria(new WhereUpdatedAtLessThan((new Carbon)->addWeek('-1')))
            ->each(function (Photo $photo) {
                $this->comment("Deleting photo 'id:{$photo->id}' ...");
                $this->photoDataProvider->delete($photo);
                $this->storage->deleteDirectory($photo->directory_path);
                $this->comment("Photo 'id:{$photo->id}' was successfully deleted.");
            });
    }
}
