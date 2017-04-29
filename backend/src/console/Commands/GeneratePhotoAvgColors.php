<?php

namespace Console\Commands;

use Closure;
use Core\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Lib\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GeneratePhotoAvgColors.
 *
 * @property AvgColorPicker avgColorPicker
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
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(AvgColorPicker $avgColorPicker)
    {
        parent::__construct();

        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->eachPhoto(function (Photo $photo) {
            $this->comment(sprintf('Generating average colors for photo (ID:%s) ...', $photo->id));
            $this->generatePhotoAvgColor($photo);
        });
    }

    /**
     * Apply callback function on each photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachPhoto(Closure $callback)
    {
        Photo::with('thumbnails')->chunk(100, function (Collection $photos) use ($callback) {
            $photos->each($callback);
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
        $thumbnail = $photo->thumbnails->first();

        $absoluteThumbnailPath = config('filesystems.disks.local.root') . '/' . $thumbnail->path;

        $photo->avg_color = $this->avgColorPicker->getImageAvgHexColorByPath($absoluteThumbnailPath);

        $this->comment(sprintf('Photo average color %s.', $photo->avg_color));

        $photo->saveOrFail();
    }
}
