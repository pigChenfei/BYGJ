<?php
namespace App\Observers;

use App\Helpers\Caches\PlayerInfoCacheHelper;
use App\Jobs\PlayerBetFlowHandleNew;
use App\Jobs\PlayerUpgradeLevelHandle;
use App\Models\CarrierPlayerLevel;
use App\Models\Conf\RebateFinancialFlowAgentGamePlatConf;
use App\Models\Def\Game;
use App\Models\Log\AgentRebateFinancialFlow;
use \App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Models\Conf\CarrierCommissionAgentPlatformFee;
use App\Models\Log\CarrierAgentSettlePeriodsLog;
use App\Models\CarrierAgentUser;

class PlayerBetFlowObserver
{

    const RATIOFULL = 100;

    public function saved(PlayerBetFlowLog $playerBetFlowLog)
    {
        \DB::beginTransaction();
        try {
            if ($playerBetFlowLog->bet_flow_available && $playerBetFlowLog->available_bet_amount > 0 && $playerBetFlowLog->is_handle == 0) {
                // 玩家升级队列处理
                dispatch(new PlayerUpgradeLevelHandle(PlayerInfoCacheHelper::getPlayerCacheInfoById($playerBetFlowLog->player_id)));
                // 对会员的游戏记录进行取款流水限制处理
                dispatch(new PlayerBetFlowHandleNew($playerBetFlowLog));
                // 生成会员的洗码记录
                $this->handle($playerBetFlowLog);
                $playerBetFlowLog->is_handle = 1;
                $playerBetFlowLog->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('', [
                'PlayerBetFlowObserver' => $e->getMessage()
            ]);
        }
    }

    /**
     * 生成会员的洗码记录
     *
     * @param PlayerBetFlowLog $playerBetFlowLog
     */
    public function handle(PlayerBetFlowLog $playerBetFlowLog)
    {
        if ($playerBetFlowLog->bet_flow_available == false) {
            \WLog::error('', [
                '不是有效投注' => $playerBetFlowLog->id
            ]);
            return;
        }
        $playerGameAccount = PlayerGameAccount::byPlayerId($playerBetFlowLog->player_id)->with([
            'player.playerLevel',
            'player.agent'
        ])->first();
        if (! $playerGameAccount) {
            \WLog::error('', [
                '会员游戏账户不存在' => $playerBetFlowLog->player_id
            ]);
            return;
        }
        $game = Game::where('game_id', $playerBetFlowLog->game_id)->with('gamePlat.mainGamePlat')->first();
        if (! $game) {
            \WLog::error('', [
                '游戏平台' => $playerBetFlowLog->game_id
            ]);
            return;
        }
        $result = $this->agentHandle($playerBetFlowLog, $playerGameAccount, $game); // 先计算代理洗码
        if ($result == false) {
            \WLog::error('代理洗码出错');
            return;
        }
        
        if ($this->isGamePlatCanEnjoyRebateFinancialFlow($playerGameAccount, $game) == false) { // 判断是否享受反水
            \WLog::error('', [
                '用户不享受反水' => $playerGameAccount
            ]);
            return;
        }
        $player = $playerGameAccount->player;
        $playerLevelRebateFinancialFlowConfigure = CarrierPlayerLevel::cacheRebateFinancialFlow($player->player_level_id, $game->game_plat_id);
        \WLog::info('会员等级设置', [
            'setting' => $playerLevelRebateFinancialFlowConfigure
        ]);
        if (! $playerLevelRebateFinancialFlowConfigure) {
            \WLog::error('', [
                '会员等级游戏配置不存在' => $player->player_level_id
            ]);
            return;
        }
        
        // 查询洗码周期内的洗码日志
        $log = PlayerRebateFinancialFlowNew::where('log_player_bet_flow_id', $playerBetFlowLog->id)->first();
        if ($log) {
            \WLog::error('', [
                '该记录已生成' => $log
            ]);
            return;
        }
        // 如果没有洗码日志, 那么生成洗码日志
        $log = new PlayerRebateFinancialFlowNew();
        $log->player_id = $player->player_id;
        $log->carrier_id = $player->carrier_id;
        $log->game_plat = $game->game_plat_id;
        $log->log_player_bet_flow_id = $playerBetFlowLog->id;
        $log->bet_flow_amount = $playerBetFlowLog->available_bet_amount;
        $log->company_pay_out_amount = $playerBetFlowLog->company_payout_amount;
        $log->is_already_settled = 0;
        $log->rebate_manual_period_hours = $playerLevelRebateFinancialFlowConfigure->rebate_manual_period_hours;
        $log->rebate_type = $playerLevelRebateFinancialFlowConfigure->rebate_type;
        
        // 计算洗码额;
        $rebateFinancialFlowAmount = 0;
        if ($stepRateArray = $playerLevelRebateFinancialFlowConfigure->flowStepRateFormatArray()) {
            // 按照阶梯比例计算;
            foreach ($stepRateArray as $item) {
                if ($playerBetFlowLog->available_bet_amount >= $item['flowAmount']) {
                    $rebateFinancialFlowAmount = bcmul($playerBetFlowLog->available_bet_amount, bcdiv($item['flowRate'], self::RATIOFULL, 4), 2);
                }
            }
        } else {
            // 否则按照总洗码比例来执行;
            $rebateFinancialFlowRatio = bcdiv($playerLevelRebateFinancialFlowConfigure->rebate_financial_flow_rate, self::RATIOFULL, 4);
            $rebateFinancialFlowAmount = bcmul($playerBetFlowLog->available_bet_amount, $rebateFinancialFlowRatio, 2);
            if (bccomp($rebateFinancialFlowAmount, 0.00, 2) <= 0) {
                \WLog::error('有效投注额：' . $playerBetFlowLog->available_bet_amount . ',洗码金额：' . $rebateFinancialFlowAmount . '，不记录,洗码比例：' . $rebateFinancialFlowRatio);
                return;
            }
        }
        // 检测洗码是否超过单次限额
        $maxFlowAmount = $playerLevelRebateFinancialFlowConfigure->limit_amount_per_flow;
        if (bccomp($maxFlowAmount, 0.00, 2) > 0) {
            // 如果超过最大限额;那么如果本身之前的返水就大于最大限额(有可能运营商这个最大限额是后期设置的情况),那么还是维持用户之前的最大限额不变;否则设置为最大额度即可;
            if (bccomp($rebateFinancialFlowAmount, $maxFlowAmount, 2) >= 0) {
                $log->rebate_financial_flow_amount = $maxFlowAmount;
            }
        } else {
            $log->rebate_financial_flow_amount = $rebateFinancialFlowAmount;
        }
        $agent = $playerGameAccount->player->agent;
        if ($agent->isCarrierDefaultAgent()) {
            $log->agent_out_amount = 0;
            $log->company_out_amount = $log->rebate_financial_flow_amount;
        } else {
            $rebateFinancialFlowAgentConf = $agent->agentLevel->commissionAgentConf;
            $agentAccount = bcmul($log->rebate_financial_flow_amount, bcdiv($rebateFinancialFlowAgentConf->rebate_financial_flow_undertake_ratio, self::RATIOFULL, 4), 2);
            if (bccomp($agentAccount, $rebateFinancialFlowAgentConf->rebate_financial_flow_undertake_max, 2) > 0 &&
                 bccomp($rebateFinancialFlowAgentConf->rebate_financial_flow_undertake_max, 0.00, 2) > 0) {
                
                $log->agent_out_amount = $rebateFinancialFlowAgentConf->rebate_financial_flow_undertake_max;
                $log->company_out_amount = bcsub($log->rebate_financial_flow_amount, $rebateFinancialFlowAgentConf->rebate_financial_flow_undertake_max, 2);
            } else {
                $log->agent_out_amount = $agentAccount;
                $log->company_out_amount = bcsub($log->rebate_financial_flow_amount, $agentAccount, 2);
            }
        }
        $log->save();
    }

    private function agentHandle(PlayerBetFlowLog $playerBetFlowLog, PlayerGameAccount $playerGameAccount, Game $game)
    {
        try {
            $agent = CarrierAgentUser::with('agentLevel')->find($playerGameAccount->player->agent_id);
            if (! $agent) {
                \WLog::error('', [
                    '代理不存在' => $agent
                ]);
                return true;
            }
            \WLog::info('代理存在，计算代理洗码');
            if ($agent->isCarrierDefaultAgent()) {
                \WLog::error('', [
                    '当前用户代理为默认代理不存在' => $agent
                ]);
                return true;
            }
            if ($agent->isCostTakenAgent() && $agent->agentLevel->costTakeAgentConf->is_player_rebate_financial_adapt_carrier_conf == false) {
                // 如果是占成代理,并且不按照网站的优惠配置, 那么跳过此洗码处理;
                \WLog::info('当前代理是占成代理, 不处理洗码');
            } elseif ($agent->isRebateFinancialFlowAgent()) {
                // 如果是洗码代理
                \WLog::info('当前代理是洗码代理');
                // 如果不按照网站返水配置计算,那么按照代理自己的配置处理;
                $adaptConf = $agent->agentLevel->rebateFinancialFlowAgentBaseConf->is_player_rebate_financial_adapt_carrier_conf;
                $rebateFinancialFlowAgentConf = RebateFinancialFlowAgentGamePlatConf::byAgentId($agent->id)->byGamePlatId($game->game_plat_id)->first();
                \WLog::info('当前洗码代理已设置按照自身洗码比例处理数据');
                if (! $rebateFinancialFlowAgentConf) {
                    \WLog::error('未找到该洗码代理的游戏平台洗码配置数据: agent_id:' . $agent->id . ' game_plat_id:' . $game->game_plat_id);
                    return false;
                }
                // 处理代理返水数据
                return $this->handleAgentRebateFinancial($rebateFinancialFlowAgentConf, $game, $playerBetFlowLog, $agent, $adaptConf, $playerGameAccount->player);
                // \WLog::info('更新代理会员洗码数据成功!' . $record->gameCode . ' ' . $record->playerName);
            } elseif ($agent->isCommissionAgent()) {
                \WLog::info('佣金代理洗码开始======');
                $where = array(
                    'carrier_id' => $agent->carrier_id,
                    'computing_mode_2' => 1,
                    'agent_level_id' => $agent->agent_level_id,
                    'carrier_game_plat_id' => $game->game_plat_id
                );
                $conf = CarrierCommissionAgentPlatformFee::where($where)->first();
                $this->createAgenRebatetLog($agent, $playerBetFlowLog, $conf, $game);
            }
        } catch (\Exception $e) {
            \WLog::error('', [
                '代理洗码失败，继续执行玩家洗码.' => $e->getMessage()
            ]);
            return true;
        }
        return true;
    }

    /**
     * 游戏平台是否能够享受返水
     *
     * @param PlayerGameAccount $playerGameAccount
     * @param Game $game
     * @return bool
     */
    private function isGamePlatCanEnjoyRebateFinancialFlow(PlayerGameAccount $playerGameAccount, Game $game)
    {
        // 获取会员最早的有限游戏平台并且有参与活动的未完成流水限制
        $withdrawFlowLimitWithGameLimit = PlayerWithdrawFlowLimitLog::byPlayerId($playerGameAccount->player_id)->with(
            [
                'limitGamePlats',
                'carrierActivity'
            ])
            ->hasActivity()
            ->earliestUnfinishedLog()
            ->first();
        // 检测关联的活动是否享受洗码
        if ($withdrawFlowLimitWithGameLimit && $withdrawFlowLimitWithGameLimit->carrierActivity->is_bet_amount_enjoy_flow == false) {
            // 检测是否有游戏平台限制
            if ($withdrawFlowLimitWithGameLimit->limitGamePlats->count() > 0) {
                // 如果该游戏是在游戏平台中,那么在该游戏平台列表中的游戏洗码是无效的;
                $gamePlatsId = $withdrawFlowLimitWithGameLimit->limitGamePlats->each(
                    function (PlayerWithdrawFlowLimitLogGamePlat $element) {
                        return $element->def_game_plat_id;
                    })->toArray();
                if (in_array($game->gamePlat->game_plat_id, $gamePlatsId)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 洗码代理
     *
     * @param RebateFinancialFlowAgentGamePlatConf $conf
     * @param Game $game
     * @param GameGatewayBetFlowRecord $record
     * @param CarrierAgentUser $agent
     */
    private function handleAgentRebateFinancial(RebateFinancialFlowAgentGamePlatConf $conf, Game $game, PlayerBetFlowLog $record, CarrierAgentUser $agent, $adaptConf,
        Player $player)
    {
        $log = new AgentRebateFinancialFlow();
        $log->carrier_id = $agent->carrier_id;
        $log->agent_id = $agent->id;
        $log->log_player_bet_flow_id = $record->playerBetFlowDBId;
        $log->flow_rate = $conf->agent_rebate_financial_flow_rate;
        $log->game_plat_id = $game->game_plat_id;
        $rebateAmountTotal = bcmul($record->bet, bcdiv($conf->agent_rebate_financial_flow_rate, 100, 5), 2);
        if ($adaptConf == 0 || $adaptConf == false) {
            $playerRebateAmount = $this->handlePlayerRebateFinancial($rebateAmountTotal, $conf, $game, $record, $player);
            $log->amount = bcmul($rebateAmountTotal, $playerRebateAmount, 2);
        } else {
            $log->amount = $rebateAmountTotal;
        }
        
        // 计算该代理商这个游戏平台是否达到最大限额;
        // if ($conf->agent_rebate_financial_flow_max_amount > 0) {
        // $maxAmount = AgentRebateFinancialFlow::byAgentId($agent->id)->gamePlatId($game->game_plat_id)
        // ->unsettled()
        // ->sum('amount');
        // if ($maxAmount > $conf->agent_rebate_financial_flow_max_amount) {
        // $log->amount = $conf->agent_rebate_financial_flow_max_amount - $maxAmount;
        // }
        // }
        $log->save();
        return $adaptConf == 0 || $adaptConf == false ? true : false;
    }

    /**
     * 处理代理设置的下属会员洗码数据
     *
     * @param RebateFinancialFlowAgentGamePlatConf $conf
     * @param Game $game
     * @param GameGatewayBetFlowRecord $record
     * @param Player $player
     */
    private function handlePlayerRebateFinancial($totalAmount, RebateFinancialFlowAgentGamePlatConf $conf, Game $game, PlayerBetFlowLog $playerBetFlowLog, Player $player)
    {
        if ($conf->player_rebate_financial_flow_rate > 0) {
            $playerLevelRebateFinancialFlowConfigure = CarrierPlayerLevel::cacheRebateFinancialFlow($player->player_level_id, $game->game_plat_id);
            if (! $playerLevelRebateFinancialFlowConfigure) {
                \WLog::error('该会员等级对应的投注流水无配置数据: player_level_id:' . $player->player_level_id, ' game_plat_id:' . $game->game_plat_id);
                return 0;
            }
            // 查询洗码周期内的洗码日志
            $log = PlayerRebateFinancialFlowNew::where('log_player_bet_flow_id', $playerBetFlowLog->id)->where('player_id', $player->id)->first();
            if ($log) {
                \WLog::error('该洗码记录已经存在: log_id:' . $log->id, ' game_plat_id:' . $game->game_plat_id . ',会员编号：' . $player->id);
                return 0;
            }
            
            $log->player_id = $player->player_id;
            $log->carrier_id = $player->carrier_id;
            $log->game_plat = $game->game_plat_id;
            $log->log_player_bet_flow_id = $playerBetFlowLog->id;
            $log->bet_flow_amount = $playerBetFlowLog->available_bet_amount;
            $log->company_pay_out_amount = $playerBetFlowLog->company_payout_amount;
            $log->is_already_settled = 0;
            $log->rebate_manual_period_hours = 0;
            $log->rebate_type = 2;
            
            $maxFlowAmount = $conf->player_rebate_financial_flow_max_amount;
            $log->rebate_financial_flow_amount = bcmul($totalAmount, bcdiv($conf->player_rebate_financial_flow_rate, self::RATIOFULL, 5), 2);
            // 检测洗码是否超过代理配置的单次限额
            if ($maxFlowAmount > 0) {
                if ($log->rebate_financial_flow_amount >= $maxFlowAmount) {
                    $log->rebate_financial_flow_amount = $maxFlowAmount;
                }
            }
            $log->save();
            return bcsub($totalAmount, $log->rebate_financial_flow_amount);
        }
    }

    /**
     * 代理洗码记录
     *
     * @param CarrierAgentUser $agent
     * @param PlayerBetFlowLog $playerBetFlowLog
     * @param unknown $conf
     * @param unknown $game
     */
    private function createAgenRebatetLog(CarrierAgentUser $agent, PlayerBetFlowLog $playerBetFlowLog, $conf, $game)
    {
        if (! empty($conf)) {
            \WLog::info('佣金代理洗码入库操作');
            $log = new AgentRebateFinancialFlow();
            $log->carrier_id = $agent->carrier_id;
            $log->agent_id = $agent->id;
            $log->log_player_bet_flow_id = $playerBetFlowLog->id;
            $log->flow_rate = $conf->agent_rebate_financial_flow_rate;
            $log->game_plat_id = $game->game_plat_id;
            $log->amount = bcmul($playerBetFlowLog->available_bet_amount, bcdiv($conf->agent_rebate_financial_flow_rate, 100, 4), 2);
            $log->cathectic = $playerBetFlowLog->bet_amount;
            $log->available_cathectic = $playerBetFlowLog->available_bet_amount;
            // 计算该代理商这个游戏平台是否达到最大限额;
            // if ($conf->agent_rebate_financial_flow_max_amount > 0) {
            // $carrierCommissionSettlePeriodsLog = CarrierAgentSettlePeriodsLog::where([
            // 'carrier_id' => \WinwinAuth::carrierUser()->carrier_id,
            // 'agent_id' => $agent->id
            // ])->orderBy('created_at', 'desc')->first();
            // $query = AgentRebateFinancialFlow::byAgentId($agent->id)->byGamePlatId($game->game_plat_id);
            // if (! empty($carrierCommissionSettlePeriodsLog)) {
            // if (str_contains('至', $carrierCommissionSettlePeriodsLog->periods)) {
            // $periodsTime = explode("至", $carrierCommissionSettlePeriodsLog->periods);
            // $limitTime = $periodsTime[1];
            // } else {
            // $limitTime = $carrierCommissionSettlePeriodsLog->periods;
            // }
            // $query->where('created_at', '>=', $limitTime);
            // }
            // $nowAmount = $query->unsettled()->sum('amount');
            
            // if (bccomp($nowAmount, $conf->agent_rebate_financial_flow_max_amount) >= 0) {
            // $log->amount = 0;
            // } elseif (bccomp($nowAmount, $conf->agent_rebate_financial_flow_max_amount) < 0 && bccomp(bcadd($log->amount, $nowAmount, 2), $conf->agent_rebate_financial_flow_max_amount) > 0) {
            // $log->amount = bcsub($conf->agent_rebate_financial_flow_max_amount, $nowAmount, 2);
            // }
            // }
            $log->save();
        }
    }
}

