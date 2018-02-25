<?php

namespace App\Console\Commands;

use App\Console\Schedules\PassPlayerRebateFinancialFlowSchedule;
use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use Illuminate\Console\Command;

class PassPlayerRebateFinancialFlowWeekCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passPlayerRebateFinancialFlow:week';

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
        return false;
        (new PassPlayerRebateFinancialFlowSchedule(CarrierPlayerGamePlatRebateFinancialFlow::REBATE_MANUAL_PERIOD_WEEK))->run();
    }
}
