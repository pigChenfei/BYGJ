<?php

namespace App\Console\Commands;

use App\Console\Schedules\PassPlayerRebateFinancialFlowSchedule;
use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use Illuminate\Console\Command;

class HandlePlayerRebateFinancialFlowExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handlePlayerRebateFinancialFlowExpired';

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
        $playerRebateFinancialFlows = PlayerRebateFinancialFlowNew::where('is_already_settled',0)
            ->where('is_effect', 0)->get();
        if ($playerRebateFinancialFlows->count() > 0){
            $playerRebateFinancialFlows->each(function (PlayerRebateFinancialFlowNew $log){
                if ($log->rebate_manual_period_hours != 0 && ((time() - strtotime($log->created_at)) > ($log->rebate_manual_period_hours * 3600)) ){
                    $log->is_effect = 1;
                    $log->update();
                }
            });
        }
    }
}
