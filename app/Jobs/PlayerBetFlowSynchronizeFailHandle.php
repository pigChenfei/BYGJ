<?php

namespace App\Jobs;

use App\Models\System\ReminderEmail;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use App\Vendor\GameGateway\Gateway\GameGateway;
use App\Vendor\GameGateway\Gateway\GameGatewayInterface;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerBetFlowSynchronizeFailHandle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var GameGatewayInterface
     */
    private $gameGateWay;

    /**
     * @var GameGatewaySearchCondition
     */
    private $searchCondition;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GameGatewayInterface $gameGateWay,GameGatewaySearchCondition $searchCondition)
    {
        $this->gameGateWay = $gameGateWay;
        $this->searchCondition = $searchCondition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return false;
        //同步游戏流水记录
        \WLog::info('开始处理失败的投注请求');
        $record = $this->gameGateWay->synchronizeGameFlowToDB($this->searchCondition,null);
//        $recordsCopy = $record;
        dispatch(new PlayerBetFlowHandle($record));
//        dispatch(new PlayerRebateFinancialFlowHandle($recordsCopy));
    }

    /**
     * 要处理的失败任务。
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $e)
    {   \WLog::error('处理失败的请求也处理失败了');
        dispatch(new SendReminderEmail(new ReminderEmail($e)));
    }

}
