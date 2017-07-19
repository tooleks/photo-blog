<?php

namespace Console\Commands;

use Carbon\Carbon;
use App\Services\Trash\Contracts\TrashService;
use Illuminate\Console\Command;

/**
 * Class CleanTrash.
 *
 * @package Console\Commands
 */
class ClearTrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:trash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the trash';

    /**
     * @var TrashService
     */
    protected $trashService;

    /**
     * Create a new command instance.
     * @param TrashService $trashService
     */
    public function __construct(TrashService $trashService)
    {
        parent::__construct();

        $this->trashService = $trashService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->trashService->clear(Carbon::now()->addDay(-1)->timestamp);
    }
}
