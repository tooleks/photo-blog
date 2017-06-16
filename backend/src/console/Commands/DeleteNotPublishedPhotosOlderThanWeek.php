<?php

namespace Console\Commands;

use Carbon\Carbon;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\Models\Photo;
use Closure;
use Illuminate\Console\Command;
use Lib\DataProvider\Criterias\WhereUpdatedAtLessThan;

/**
 * Class DeleteNotPublishedPhotosOlderThanWeek.
 *
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
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->eachNotPublishedPhotoOlderThanWeek(function (Photo $photo) {
            $this->comment("Deleting [photo:{$photo->id}] ...");
            $this->photoDataProvider->delete($photo);
        });
    }

    /**
     * Apply callback function on each not published photo older than week.
     *
     * @param Closure $callback
     * @return void
     */
    protected function eachNotPublishedPhotoOlderThanWeek(Closure $callback)
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->applyCriteria(new WhereUpdatedAtLessThan((new Carbon)->addWeek('-1')))
            ->each($callback);
    }
}
