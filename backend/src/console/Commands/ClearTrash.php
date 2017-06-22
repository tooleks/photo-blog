<?php

namespace Console\Commands;

use Carbon\Carbon;
use Core\Managers\Trash\Contracts\TrashManager;
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
     * @var TrashManager
     */
    protected $trashManager;

    /**
     * Create a new command instance.
     * @param TrashManager $trashManager
     */
    public function __construct(TrashManager $trashManager)
    {
        parent::__construct();

        $this->trashManager = $trashManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->trashManager->clear(Carbon::now()->addDay(-1)->timestamp);
    }
}
