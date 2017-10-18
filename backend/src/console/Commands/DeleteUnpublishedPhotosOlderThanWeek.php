<?php

namespace Console\Commands;

use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use Illuminate\Console\Command;

/**
 * Class DeleteUnpublishedPhotosOlderThanWeek.
 *
 * @package Console\Commands
 */
class DeleteUnpublishedPhotosOlderThanWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unpublished_photos_older_than_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unpublished photos older than week';

    /**
     * @var PhotoManager
     */
    protected $photoManager;

    /**
     * DeleteUnpublishedPhotosOlderThanWeek constructor.
     *
     * @param PhotoManager $photoManager
     */
    public function __construct(PhotoManager $photoManager)
    {
        parent::__construct();

        $this->photoManager = $photoManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->photoManager->eachUnpublishedGreaterThanWeekAgo(function (Photo $photo) {
            $this->comment("Deleting photo {$photo->id}");
            $this->photoManager->delete($photo);
        });
    }
}
