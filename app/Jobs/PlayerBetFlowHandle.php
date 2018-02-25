<?php
namespace App\Jobs;

use App\Exceptions\PlayerAccountException;
use App\Models\Def\Game;
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
class PlayerBetFlowHandle implements ShouldQueue
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
    public function __construct($betFlowRecord)
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
        return false;
        foreach ($this->betFlowRecord as &$record) {
            try {
                // 如果不是有效投注流水,那么不处理
                if ($record->isBetAvailable == false) {
                    continue;
                }
                $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record->playerName);
                if (! $playerGameAccount) {
                    throw new PlayerAccountException('无法找到该会员的游戏账户:' . $record->playerName);
                }
                $game = Game::getCachedGameByGameCode($record->gameCode);
                if (! $game) {
                    throw new PlayerAccountException('无法找到该游戏:' . $record->gameCode);
                }
                $withdrawFlowLimitDetail = null;
                do {
                    // 获取会员最早的未完成流水限制
                    $withdrawFlowLimit = PlayerWithdrawFlowLimitLog::byPlayerId($playerGameAccount->player_id)->earliestUnfinishedLog()->first();
                    // 获取会员最近未完成有游戏流水限制平台的的流水限制
                    $withdrawFlowLimitWithGameLimit = PlayerWithdrawFlowLimitLog::has('limitGamePlats', '>=', '1')->byPlayerId($playerGameAccount->player_id)
                        ->with('limitGamePlats')
                        ->earliestUnfinishedLog()
                        ->first();
                    
                    $withdrawFlowLimit = $withdrawFlowLimit ?: $withdrawFlowLimitWithGameLimit;
                    
                    if ($withdrawFlowLimit && $withdrawFlowLimit->limitGamePlats && $withdrawFlowLimit->limitGamePlats->count() > 0) {
                        $gamePlatsId = $withdrawFlowLimit->limitGamePlats->map(function (PlayerWithdrawFlowLimitLogGamePlat $element) {
                            return $element->def_game_plat_id;
                        })->toArray();
                        // 如果没有在限流水的游戏平台中,那么该投注流水应该是无效的
                        if (! in_array($game->game_plat_id, $gamePlatsId)) {
                            \WLog::info('当前游戏不在限流水游戏平台中,流水无效 player_id:' . $playerGameAccount->player_id . ' game_plat_id:' . $game->game_plat_id . ' flow_limit_log_id:' . $withdrawFlowLimit->id . ' 限流水平台:', $gamePlatsId);
                            break;
                        }
                    }
                    
                    if (! $withdrawFlowLimit) {
                        \WLog::info('该用户的流水限制都已经完成,跳过处理... player_id:' . $playerGameAccount->player_id . ' game_plat_id:' . $game->game_plat_id);
                        // 当没有更多的限制流水记录时,保存最后一次的流水详细记录
                        if ($withdrawFlowLimitDetail) {
                            $withdrawFlowLimitDetail->save();
                            \WLog::info('保存流水限制游戏平台数据成功 game_plat_id:' . $game->game_plat_id . ' game_name:' . $game->game_name);
                        }
                        break;
                    }
                    
                    if ($withdrawFlowLimitDetail && ($withdrawFlowLimitDetail->withdraw_flow_limit_id != $withdrawFlowLimit->id || $withdrawFlowLimitDetail->game_plat_id != $game->game_plat_id)) {
                        $withdrawFlowLimitDetail->save();
                        $withdrawFlowLimitDetail = null;
                    }
                    
                    // 限流水平台详细数据
                    if (! $withdrawFlowLimitDetail) {
                        $withdrawFlowLimitDetail = PlayerWithdrawFlowLimitLogDetail::byFlowLimitLogId($withdrawFlowLimit->id)->byGamePlatId($game->game_plat_id)->first();
                        if (! $withdrawFlowLimitDetail) {
                            $withdrawFlowLimitDetail = new PlayerWithdrawFlowLimitLogDetail();
                            $withdrawFlowLimitDetail->withdraw_flow_limit_id = $withdrawFlowLimit->id;
                            $withdrawFlowLimitDetail->game_plat_id = $game->game_plat_id;
                            $withdrawFlowLimitDetail->game_id = $game->game_id;
                        }
                    }
                    $oldFlowAmount = $withdrawFlowLimit->complete_limit_amount;
                    \WLog::info(' BET OLD:' . $record->bet . ' AMOUNT OLD:' . $oldFlowAmount);
                    // 一直累加投注额流水
                    $record->bet = $this->updateFlowLimitRecordWithRemainAmount($withdrawFlowLimit, $record->bet);
                    $newFlowAmount = $withdrawFlowLimit->complete_limit_amount;
                    $withdrawFlowLimitDetail->flow_amount += bcsub($newFlowAmount, $oldFlowAmount, 2);
                    \WLog::info(' BET NEW:' . $record->bet . ' AMOUNT NEW:' . $newFlowAmount . ' FLOW_DETAIL_AMOUNT:' . $withdrawFlowLimitDetail->flow_amount);
                    
                    // 当流水已经新增为0的时候,说明当前流水处理完毕,那么保存详细流水限制;重新开始下一轮
                    \WLog::info('====>当前处理投注额:' . $record->bet);
                    if ($record->bet <= 0 && $withdrawFlowLimitDetail) {
                        $withdrawFlowLimitDetail->save();
                        \WLog::info('保存流水限制游戏平台数据成功 game_plat_id:' . $game->game_plat_id . ' game_name:' . $game->game_name);
                    }
                    \WLog::info('更新流水限制成功!' . $record->gameCode . ' ' . $record->playerName);
                } while ($record->bet > 0);
            } catch (\Exception $e) {
                dispatch(new SendReminderEmail(new ReminderEmail($e)));
                \WLog::error('更新流水限制失败!' . $record->gameCode . ' ' . $record->playerName . ' error:' . $e->getMessage());
            }
            \WLog::info('------------------------END------------------------');
        }
        unset($record);
    }

    private function updateFlowLimitRecordWithRemainAmount(PlayerWithdrawFlowLimitLog &$log, $amount)
    {
        $temp_complete_limit_amount = $log->complete_limit_amount + $amount;
        if ($temp_complete_limit_amount >= $log->limit_amount) {
            $log->complete_limit_amount = $log->limit_amount;
            $log->is_finished = true;
        } else {
            $log->complete_limit_amount += $amount;
        }
        $log->update();
        return $temp_complete_limit_amount - $log->limit_amount;
    }
}
