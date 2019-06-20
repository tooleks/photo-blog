<?php

namespace Console\Commands;

use App\Models\Photo;
use App\Services\Image\Contracts\ImageProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class GeneratePhotosMetadata.
 *
 * @package Console\Commands
 */
class GeneratePhotosMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:photos-metadata
                                {--chunk_size=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate photos metadata';

    /**
     * Execute the console command.
     *
     * @param ImageProcessor $imageProcessor
     * @return void
     */
    public function handle(ImageProcessor $imageProcessor): void
    {
        (new Photo)
            ->newQuery()
            ->chunk($this->option('chunk_size'), function (Collection $photos) use ($imageProcessor) {
                $photos->each(function (Photo $photo) use ($imageProcessor) {
                    try {
                        $this->comment("Processing photo {$photo->id}...");
                        $photo->metadata = $imageProcessor->open($photo->path)->getMetadata();
                        $photo->save();
                    } catch (Throwable $e) {
                        $this->error($e->getMessage());
                    }
                });
            });
    }
}
