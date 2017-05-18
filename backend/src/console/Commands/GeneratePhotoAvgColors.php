<?php

namespace Console\Commands;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GeneratePhotoAvgColors.
 *
 * @property Storage storage
 * @property AvgColorPicker avgColorPicker
 * @property PhotoDataProvider photoDataProvider
 * @package Console\Commands
 */
class GeneratePhotoAvgColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:photo_avg_colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate photo "avg_color" field values by thumbnail files';

    /**
     * GeneratePhotoAvgColors constructor.
     *
     * @param Storage $storage
     * @param AvgColorPicker $avgColorPicker
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Storage $storage, AvgColorPicker $avgColorPicker, PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->avgColorPicker = $avgColorPicker;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->photoDataProvider->each(function (Photo $photo) {
            $this->comment("Generating average photo color (id:{$photo->id}) ...");
            $this->generatePhotoAvgColor($photo);
            $this->comment("Average photo color was successfully generated (id:{$photo->id}).");
        });
    }

    /**
     * Generate photo average color.
     *
     * @param Photo $photo
     * @return void
     */
    public function generatePhotoAvgColor(Photo $photo)
    {
        $storageAbsPath = $this->storage->getDriver()->getAdapter()->getPathPrefix();

        $thumbnailAbsPath = $storageAbsPath . $photo->thumbnails->first()->path;

        $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath($thumbnailAbsPath);

        $this->photoDataProvider->save($photo);
    }
}
