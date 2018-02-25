<?php

namespace App\Jobs;

use App\Services\PassPlayerRebateFinancialFlowService;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * 发放玩家返水数据
 * Class PassPlayerRebateFinancialFlow
 * @package App\Jobs
 */
class PassPlayerRebateFinancialFlow implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Collection
     */
    private $rebateFinancialFlowLogs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $rebateFinancialFlowLogs)
    {
        $this->rebateFinancialFlowLogs = $rebateFinancialFlowLogs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return false;
        \WLog::info('开始自动处理玩家洗码记录');
        try{
            $handleService = new PassPlayerRebateFinancialFlowService($this->rebateFinancialFlowLogs);
            $handleService->handle();
        }catch (\Exception $e){
            \WLog::error('玩家自动处理洗码数据失败:'.$e->getMessage());
        }
    }
}
