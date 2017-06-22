<?php

namespace Console\Commands;

use Core\Managers\Photo\Contracts\PhotoManager;
use Core\Models\Photo;
use Illuminate\Console\Command;

/**
 * Class DeleteNotPublishedPhotosOlderThanWeek.
 *
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
     * @var PhotoManager
     */
    protected $photoManager;

    /**
     * DeleteNotPublishedPhotosOlderThanWeek constructor.
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
    public function handle()
    {
        $this->photoManager->eachNotPublishedPhotoOlderThanWeek(function (Photo $photo) {
            $this->comment("Deleting photo [id:{$photo->id}] ...");
            $this->photoManager->deleteWithRelations($photo);
        });
    }
}
