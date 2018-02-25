<?php
namespace App\Jobs;

use App\Exceptions\PlayerAccountException;
use App\Models\Def\Game;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogDetail;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Models\PlayerGameAccount;
use App\Models\System\ReminderEmail;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 对会员的游戏记录进行取款流水限制处理
 * Class PlayerBetFlowHandle
 *
 * @package App\Jobs
 */
class PlayerBetFlowHandleNew implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var GameGatewayBetFlowRecord[]
     */
    private $betFlowRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlayerBetFlowLog $betFlowRecord)
    {
        $this->betFlowRecord = $betFlowRecord;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \WLog::info('用户限制流水处理===============>start<=============' . $this->betFlowRecord->player_id);
            // 如果不是有效投注流水,那么不处理
            if ($this->betFlowRecord->bet_flow_available == false) {
                \WLog::info('该流水不是有效流水player_bet_flow_id:' . $this->betFlowRecord->id);
                throw new PlayerAccountException('该流水不是有效流水player_bet_flow_id:' . $this->betFlowRecord->id);
            }
            $playerGameAccount = PlayerGameAccount::where('player_id', $this->betFlowRecord->player_id)->with(
                [
                    'player.playerLevel',
                    'player.agent'
                ])->first();
            if (! $playerGameAccount) {
                \WLog::info('无法找到该会员的游戏账户player_id:' . $this->betFlowRecord->player_id);
                throw new PlayerAccountException('无法找到该会员的游戏账户player_id:' . $this->betFlowRecord->player_id);
            }
            $game = Game::where('game_id', $this->betFlowRecord->game_id)->with('gamePlat.mainGamePlat')->first();
            if (! $game) {
                \WLog::info('无法找到该游戏game_id:' . $this->betFlowRecord->game_id);
                throw new PlayerAccountException('无法找到该游戏game_id:' . $this->betFlowRecord->game_id);
            }
            $withdrawFlowLimitDetail = null;
            do {
                // 获取会员最早的未完成流水限制
                // $withdrawFlowLimit = PlayerWithdrawFlowLimitLog::byPlayerId($playerGameAccount->player_id)->earliestUnfinishedLog()->first();
                // 获取会员最近未完成有游戏流水限制平台的的流水限制
                $withdrawFlowLimits = PlayerWithdrawFlowLimitLog::byPlayerId($playerGameAccount->player_id)->with(
                    'limitGamePlats')
                    ->unfinished()
                    ->get();
                $withdrawFlowLimit = null;
                // $withdrawFlowLimit = $withdrawFlowLimit ?: $withdrawFlowLimitWithGameLimit;
                \WLog::info('用户限制流水处理');
                foreach ($withdrawFlowLimits as $wdfl) {
                    if ($wdfl && $wdfl->limitGamePlats && $wdfl->limitGamePlats->count() > 0) {
                        $gamePlatsId = $wdfl->limitGamePlats->map(
                            function (PlayerWithdrawFlowLimitLogGamePlat $element) {
                                return $element->def_game_plat_id;
                            })->toArray();
                        // 如果没有在限流水的游戏平台中,那么该投注流水应该是无效的
                        if (! in_array($game->game_plat_id, $gamePlatsId)) {
                            continue;
                        } else {
                            \WLog::info('用户限制流水处理,获取到限制流水数据',
                                [
                                    'flow' => $wdfl
                                ]);
                            $withdrawFlowLimit = $wdfl;
                            break;
                        }
                    } else {
                        $withdrawFlowLimit = $wdfl;
                        break;
                    }
                }
                
                if (! $withdrawFlowLimit) {
                    // 当没有更多的限制流水记录时,保存最后一次的流水详细记录
                    if ($withdrawFlowLimitDetail) {
                        $withdrawFlowLimitDetail->save();
                    }
                    \WLog::info('用户限制流水处理,没有获取到相关数据');
                    break;
                }
                
                if ($withdrawFlowLimitDetail && ($withdrawFlowLimitDetail->withdraw_flow_limit_id !=
                     $withdrawFlowLimit->id || $withdrawFlowLimitDetail->game_plat_id != $game->game_plat_id)) {
                    $withdrawFlowLimitDetail->save();
                    $withdrawFlowLimitDetail = null;
                }
                
                // 限流水平台详细数据
                if (! $withdrawFlowLimitDetail) {
                    $withdrawFlowLimitDetail = PlayerWithdrawFlowLimitLogDetail::byFlowLimitLogId(
                        $withdrawFlowLimit->id)->byGamePlatId($game->game_plat_id)->first();
                    if (! $withdrawFlowLimitDetail) {
                        $withdrawFlowLimitDetail = new PlayerWithdrawFlowLimitLogDetail();
                        $withdrawFlowLimitDetail->withdraw_flow_limit_id = $withdrawFlowLimit->id;
                        $withdrawFlowLimitDetail->game_plat_id = $game->game_plat_id;
                        $withdrawFlowLimitDetail->game_id = $game->game_id;
                    }
                }
                $oldFlowAmount = $withdrawFlowLimit->complete_limit_amount;
                // 一直累加投注额流水
                $this->betFlowRecord->available_bet_amount = $this->updateFlowLimitRecordWithRemainAmount(
                    $withdrawFlowLimit, $this->betFlowRecord->available_bet_amount);
                $newFlowAmount = $withdrawFlowLimit->complete_limit_amount;
                $withdrawFlowLimitDetail->flow_amount = bcadd($withdrawFlowLimitDetail->flow_amount,
                    bcsub($newFlowAmount, $oldFlowAmount, 2), 2);
                
                // 当流水已经新增为0的时候,说明当前流水处理完毕,那么保存详细流水限制;重新开始下一轮
                if ($this->betFlowRecord->available_bet_amount <= 0 && $withdrawFlowLimitDetail) {
                    $withdrawFlowLimitDetail->save();
                }
            } while ($this->betFlowRecord->available_bet_amount > 0);
        } catch (\Exception $e) {
            \WLog::error('更新流水限制失败 player_bet_flow_id!' . $this->betFlowRecord->id . ' ' . ' error:' . $e->getMessage());
        }
    }

    private function updateFlowLimitRecordWithRemainAmount(PlayerWithdrawFlowLimitLog &$log, $amount)
    {
        $temp_complete_limit_amount = bcadd($log->complete_limit_amount, $amount, 2);
        if (bccomp($temp_complete_limit_amount, $log->limit_amount, 2) >= 0) {
            $log->complete_limit_amount = $log->limit_amount;
            $log->is_finished = 1;
        } else {
            $log->complete_limit_amount = bcadd($amount, $log->complete_limit_amount, 2);
        }
        $log->update();
        return bcsub($temp_complete_limit_amount, $log->limit_amount, 2);
    }
}
