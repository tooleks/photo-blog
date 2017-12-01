<?php

namespace Console\Commands;

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {

    }
}
