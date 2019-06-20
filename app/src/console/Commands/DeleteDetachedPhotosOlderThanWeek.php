<?php

namespace Console\Commands;

use App\Managers\Photo\ARPhotoManager;
use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Class DeleteDetachedPhotosOlderThanWeek.
 *
 * @package Console\Commands
 */
class DeleteDetachedPhotosOlderThanWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:detached-photos-older-than-week
                                {--chunk_size=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete detached photos older than week';

    /**
     * Execute the console command.
     *
     * @param ARPhotoManager $photoManager
     * @return void
     */
    public function handle(ARPhotoManager $photoManager): void
    {
        (new Photo)
            ->newQuery()
            ->whereCreatedAtLessThan(Carbon::now()->addWeek(-1))
            ->whereHasNoPosts()
            ->chunk($this->option('chunk_size'), function (Collection $photos) use ($photoManager) {
                $photos->each(function (Photo $photo) use ($photoManager) {
                    $this->comment("Deleting photo {$photo->id}...");
                    $photoManager->deleteById($photo->id);
                });
            });
    }
}
