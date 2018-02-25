<?php

namespace App\Console\Commands;

use App\Console\Schedules\PlayerInviteRewardSchedule;
use App\Models\Conf\CarrierInvitePlayerConf;
use Illuminate\Console\Command;

class HandlePlayerInviteRewardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playerInviteReward:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new PlayerInviteRewardSchedule())->run();
    }
}
