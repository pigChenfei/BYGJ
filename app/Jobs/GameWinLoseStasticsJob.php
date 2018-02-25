<?php

namespace App\Jobs;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Models\Log\GameWinLoseStastics;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerRebateFinancialFlow;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GameWinLoseStasticsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Carrier
     */
    private $carrier;

    private $gamePlatId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($carrier_id, $gamePlatId)
    {

        $this->carrier = CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($carrier_id);
        $this->gamePlatId = $gamePlatId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //当前统计10分钟之前的数据;
        $now   = Carbon::now();
        $endTime = $now->subMinutes(10);
        //如果当前时间和十分钟之前不是同一天, 那么结束时间算为昨天12点
        if($endTime->isSameDay($now) == false){
            $endTime = $now->subDay()->endOfDay();
        }
        $startTimeString = $endTime->copy()->startOfDay()->toDateTimeString();
        $endTimeString   = $endTime->copy()->toDateTimeString();
        $log = GameWinLoseStastics::whereBetween('created_at',[$startTimeString,$endTimeString])->where('carrier_id',$this->carrier->id)->where('game_plat_id',$this->gamePlatId)->first();
        if(!$log){
            $log = new GameWinLoseStastics();
            $log->carrier_id = $this->carrier->id;
            $log->game_plat_id = $this->gamePlatId;
            $log->created_at = $now->startOfDay();
        }

        $amountCollection = PlayerBetFlowLog::betFlowAvailable()
            ->gameFinished()
            ->whereBetween('created_at',[$startTimeString,$endTimeString])
            ->where('carrier_id',$this->carrier->id)
            ->where('game_plat_id',$this->gamePlatId)
            ->get(['available_bet_amount','company_win_amount']);

        //投注次数
        $log->bet_count = $amountCollection->count();

        $log->bet_amount = 0;
        $log->win_lose_amount = 0;

        $amountCollection->each(function(PlayerBetFlowLog $betFlowLog) use (&$log){
            //投注额
            $log->bet_amount += $betFlowLog->available_bet_amount;
            //公司输赢
            $log->win_lose_amount += $betFlowLog->company_win_amount;
        });

        //洗码
        $log->rebate_financial_flow_amount = PlayerRebateFinancialFlow::where('game_plat',$this->gamePlatId)
            ->where('carrier_id',$this->carrier->id)
            ->whereBetween('created_at',[$startTimeString,$endTimeString])
            ->sum('rebate_financial_flow_amount');


        //投注人数
        $log->bet_player_count = PlayerBetFlowLog::betFlowAvailable()
            ->gameFinished()
            ->whereBetween('created_at',[$startTimeString,$endTimeString])
            ->where('carrier_id',$this->carrier->id)
            ->where('game_plat_id',$this->gamePlatId)
            ->count(\DB::raw('DISTINCT player_id'));

        if($log->bet_player_count > 0){
            $log->average_bet_amount = $log->bet_amount / $log->bet_player_count;
            $log->average_bet_count  = $log->bet_count / $log->bet_player_count;
        }

        $log->save();
        \WLog::info('游戏输赢统计时间区间: '.$startTimeString.'--'.$endTimeString);


    }
}
