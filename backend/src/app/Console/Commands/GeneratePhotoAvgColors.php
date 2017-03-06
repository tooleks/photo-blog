<?php

namespace App\Console\Commands;

use Core\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Lib\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class GeneratePhotoAvgColors.
 *
 * @property AvgColorPicker avgColorPicker
 * @package App\Console\Commands
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
    protected $description = 'Generate photo "avg_colors" field values by thumbnail files';

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
     * @return mixed
     */
    public function handle()
    {
        Photo::with('thumbnails')->chunk(500, function (Collection $photos) {
            $photos->map(function (Photo $photo) {
                $this->comment(sprintf('Processing photo (ID:%s) ...', $photo->id));
                $thumbnail = $photo->thumbnails->first();
                if (is_null($thumbnail)) return $this->comment(sprintf('No thumbnails found for photo (ID:%s)', $photo->id));
                $photo->avg_color = $this->avgColorPicker->getImageAvgHexColorByPath(storage_path('app') . '/' . $thumbnail->path);
                $this->comment(sprintf('Average color: %s', $photo->avg_color));
                $photo->save();
            });
        });
    }
}
