<?php

namespace App\Jobs;

use App\Exceptions\PlayerAccountException;
use App\Models\Carrier;
use App\Models\CarrierAgentUser;
use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use App\Models\CarrierPlayerLevel;
use App\Models\Conf\RebateFinancialFlowAgentGamePlatConf;
use App\Models\Def\Game;
use App\Models\Log\AgentRebateFinancialFlow;
use App\Models\Log\PlayerRebateFinancialFlow;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Models\System\ReminderEmail;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerRebateFinancialFlowHandle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
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
        return ;
        foreach ($this->betFlowRecord as &$record){
            try{
                //如果不是有效投注流水,那么不处理
                if($record->isBetAvailable == false){
                    \WLog::info('====>该投注流水无效,不处理洗码数据');
                    continue;
                }
                $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record->playerName);
                if(!$playerGameAccount){
                    throw new PlayerAccountException('无法找到该会员的游戏账户:'.$record->playerName);
                }
                if(!$playerGameAccount->player->agent){
                    throw new PlayerAccountException('无法找到该会员的代理用户:'.$record->playerName);
                }
                $agent = $playerGameAccount->player->agent;
                $game  = Game::getCachedGameByGameCode($record->gameCode);
                if(!$game){
                    throw new PlayerAccountException('无法找到该游戏:'.$record->gameCode);
                }
                if($this->isGamePlatCanEnjoyRebateFinancialFlow($playerGameAccount,$game) == false){
                    continue;
                }
                //如果所属代理是占成代理,洗码代理, 那么需要判断玩家的返水是否按照玩家等级设置处理,否则按照占成代理和洗码代理的设置计算返水;如果是佣金代理那么还是按照运营商的玩家等级的配置计算
                if($agent->isCostTakenAgent() && $agent->agentLevel->costTakeAgentConf->is_player_rebate_financial_adapt_carrier_conf == false){
                    //如果是占成代理,并且不按照网站的优惠配置, 那么跳过此洗码处理;
                    \WLog::info('当前代理是占成代理, 不处理洗码');
                    continue;
                }
                if($agent->isRebateFinancialFlowAgent()){
                    //如果是洗码代理
                    \WLog::info('当前代理是洗码代理');
                    //如果不按照网站返水配置计算,那么按照代理自己的配置处理;
                    if($agent->agentLevel->rebateFinancialFlowAgentBaseConf->is_player_rebate_financial_adapt_carrier_conf == false){
                        \WLog::info('当前洗码代理已设置按照自身洗码比例处理数据');
                        $rebateFinancialFlowAgentConf = RebateFinancialFlowAgentGamePlatConf::byAgentId($agent->id)->byGamePlatId($game->game_plat_id)->first();
                        if(!$rebateFinancialFlowAgentConf){
                            \WLog::error('未找到该洗码代理的游戏平台洗码配置数据: agent_id:'.$agent->id.' game_plat_id:'.$game->game_plat_id);
                            continue;
                        }
                        //处理代理返水数据
                        $this->handleAgentRebateFinancialRecordWithGamePlatConfiguration($rebateFinancialFlowAgentConf,$game,$record,$agent);
                        //如果给玩家有返水设置, 那么处理玩家返水数据
                        $this->handlePlayerRebateFinancialWithAgentGamePlatConfiguration($rebateFinancialFlowAgentConf,$game,$record,$playerGameAccount->player);
                        \WLog::info('更新代理会员洗码数据成功!'.$record->gameCode.' '.$record->playerName);
                        continue;
                    }
                }
                //最终根据运营商的玩家等级配置处理返水
                \WLog::info('当前洗码代理已设置按照运营商玩家等级配置处理');
                $this->handleRebateFinancialRecordWithPlayerLevelConfiguration($playerGameAccount,$game,$record);
                \WLog::info('更新会员洗码数据成功!'.$record->gameCode.' '.$record->playerName);
            }catch (\Exception $e){
                dispatch(new SendReminderEmail(new ReminderEmail($e)));
                \WLog::error('更新洗码数据失败!'.$record->gameCode.' '.$record->playerName.' 原因:'.$e->getMessage());
                throw  $e;
            }
            \WLog::info('------------------------END------------------------');
        }
        unset($record);
    }


    /**
     * 处理代理的洗码数据
     * @param RebateFinancialFlowAgentGamePlatConf $conf
     * @param Game $game
     * @param GameGatewayBetFlowRecord $record
     * @param CarrierAgentUser $agent
     */
    private function handleAgentRebateFinancialRecordWithGamePlatConfiguration(RebateFinancialFlowAgentGamePlatConf $conf, Game $game, GameGatewayBetFlowRecord $record, CarrierAgentUser $agent){
        $log = new AgentRebateFinancialFlow();
        $log->carrier_id = $agent->carrier_id;
        $log->agent_id   = $agent->id;
        $log->log_player_bet_flow_id = $record->playerBetFlowDBId;
        $log->flow_rate = $conf->agent_rebate_financial_flow_rate;
        $log->game_plat_id = $game->game_plat_id;
        $log->amount = $record->bet * $conf->agent_rebate_financial_flow_rate * 0.01;
        //计算该代理商这个游戏平台是否达到最大限额;
        if($conf->agent_rebate_financial_flow_max_amount > 0){
            $maxAmount = AgentRebateFinancialFlow::byAgentId($agent->id)->gamePlatId($game->game_plat_id)->unsettled()->sum('amount');
            if($maxAmount > $conf->agent_rebate_financial_flow_max_amount){
                $log->amount = $conf->agent_rebate_financial_flow_max_amount - $maxAmount;
            }
        }
        $log->save();
    }


    /**
     * 处理代理设置的下属会员洗码数据
     * @param RebateFinancialFlowAgentGamePlatConf $conf
     * @param Game $game
     * @param GameGatewayBetFlowRecord $record
     * @param Player $player
     */
    private function handlePlayerRebateFinancialWithAgentGamePlatConfiguration(RebateFinancialFlowAgentGamePlatConf $conf, Game $game, GameGatewayBetFlowRecord $record, Player $player){
        if($conf->player_rebate_financial_flow_rate > 0){
            $playerLevelRebateFinancialFlowConfigure = CarrierPlayerLevel::cacheRebateFinancialFlow($player->player_level_id,$game->game_plat_id);
            if(!$playerLevelRebateFinancialFlowConfigure){
                \WLog::error('该会员等级对应的投注流水无配置数据: player_level_id:'.$player->player_level_id,' game_plat_id:'.$game->game_plat_id);
                throw new PlayerAccountException('该会员等级对应的投注流水无配置数据: player_level_id:'.$player->player_level_id,' game_plat_id:'.$game->game_plat_id);
            }
            //查询洗码周期内的洗码日志
            $log = PlayerRebateFinancialFlow::byWithinPeriodUnsettledLog($player->player_id,$playerLevelRebateFinancialFlowConfigure->rebate_manual_period_hours,$game->game_plat_id)->first();
            if(!$log){
                //如果没有洗码日志, 那么生成洗码日志
                $log = new PlayerRebateFinancialFlow();
                $log->player_id = $player->player_id;
                $log->carrier_id = $player->carrier_id;
                $log->game_plat = $game->game_plat_id;
            }
            $log->bet_times += 1;
            $log->bet_flow_amount += $record->bet;
            $log->company_pay_out_amount += $record->win;
            $maxFlowAmount = $conf->player_rebate_financial_flow_max_amount;
            //检测洗码是否超过代理配置的单次限额
            $rebateFinancialFlowAmount = $record->bet * $conf->player_rebate_financial_flow_rate * 0.01;
            if($maxFlowAmount > 0){
                if($log->rebate_financial_flow_amount + $rebateFinancialFlowAmount >= $maxFlowAmount){
                    $log->rebate_financial_flow_amount = $log->rebate_financial_flow_amount > $maxFlowAmount ? $log->rebate_financial_flow_amount : $maxFlowAmount;
                }
            }
            $log->rebate_financial_flow_amount += $rebateFinancialFlowAmount;
            $log->save();
        }
    }

    /**
     * 根据运营商会员等级配置处理会员返水处理
     * @param PlayerGameAccount $playerGameAccount
     * @param Game $game
     * @param GameGatewayBetFlowRecord $record
     * @param Float $availableBet 可以用以计算返水的投注额
     * @return PlayerRebateFinancialFlow $log 返水数据
     */
    private function handleRebateFinancialRecordWithPlayerLevelConfiguration(PlayerGameAccount $playerGameAccount, Game $game, GameGatewayBetFlowRecord $record){
        $player = $playerGameAccount->player;
        $playerLevelRebateFinancialFlowConfigure = CarrierPlayerLevel::cacheRebateFinancialFlow($player->player_level_id,$game->game_plat_id);
        if(!$playerLevelRebateFinancialFlowConfigure){
            \WLog::error('该会员等级对应的投注流水无配置数据: player_level_id:'.$player->player_level_id.' game_plat_id:'.$game->game_plat_id);
            throw new PlayerAccountException('该会员等级对应的投注流水无配置数据: player_level_id:'.$player->player_level_id.' game_plat_id:'.$game->game_plat_id);
        }
        //查询洗码周期内的洗码日志
        $log = PlayerRebateFinancialFlow::byWithinPeriodUnsettledLog($player->player_id,$playerLevelRebateFinancialFlowConfigure->rebate_manual_period_hours,$game->game_plat_id)->first();
        if(!$log){
            //如果没有洗码日志, 那么生成洗码日志
            $log = new PlayerRebateFinancialFlow();
            $log->player_id = $player->player_id;
            $log->carrier_id = $player->carrier_id;
            $log->game_plat = $game->game_plat_id;
        }
        $log->bet_times += 1;
        $log->bet_flow_amount += $record->bet;
        $log->company_pay_out_amount += $record->win;
        //计算洗码额;
        $rebateFinancialFlowAmount = 0;
        if($stepRateArray = $playerLevelRebateFinancialFlowConfigure->flowStepRateFormatArray()){
            //按照阶梯比例计算;
            foreach ($stepRateArray as $item){
                if($record->bet >= $item['flowAmount']){
                    $rebateFinancialFlowAmount = $record->bet * $item['flowRate'];
                }
            }
        }else{
            //否则按照总洗码比例来执行;
            $rebateFinancialFlowAmount = $record->bet * $playerLevelRebateFinancialFlowConfigure->rebate_financial_flow_rate * 0.01;
        }
        $maxFlowAmount = $playerLevelRebateFinancialFlowConfigure->limit_amount_per_flow;
        //检测洗码是否超过单次限额
        if($maxFlowAmount > 0){
            //如果超过最大限额;那么如果本身之前的返水就大于最大限额(有可能运营商这个最大限额是后期设置的情况),那么还是维持用户之前的最大限额不变;否则设置为最大额度即可;
            if($rebateFinancialFlowAmount + $log->rebate_financial_flow_amount >= $maxFlowAmount){
                $log->rebate_financial_flow_amount = $log->rebate_financial_flow_amount > $maxFlowAmount ? $log->rebate_financial_flow_amount : $maxFlowAmount;
            }
        }else{
            $log->rebate_financial_flow_amount += $rebateFinancialFlowAmount;
        }
        \WLog::info('处理数最终洗码:'.$log->rebate_financial_flow_amount.' 最大洗码额:'.$maxFlowAmount.' 阶梯比例数据:',$stepRateArray ?: []);
        $log->save();
    }


    /**
     * 游戏平台是否能够享受返水
     * @param PlayerGameAccount $playerGameAccount
     * @param Game $game
     * @return bool
     */
    private function isGamePlatCanEnjoyRebateFinancialFlow(PlayerGameAccount $playerGameAccount, Game $game){
        //获取会员最早的有限游戏平台并且有参与活动的未完成流水限制
        $withdrawFlowLimitWithGameLimit = PlayerWithdrawFlowLimitLog::byPlayerId($playerGameAccount->player_id)->with(['limitGamePlats','carrierActivity'])->hasActivity()->earliestUnfinishedLog()->first();
        //检测关联的活动是否享受洗码
        \WLog::info('检测该投注流水是否有关联活动');
        if($withdrawFlowLimitWithGameLimit && $withdrawFlowLimitWithGameLimit->carrierActivity->is_bet_amount_enjoy_flow == false){
            //检测是否有游戏平台限制
            \WLog::info('该投注流水有关联活动,检测是否有限游戏平台洗码');
            if($withdrawFlowLimitWithGameLimit->limitGamePlats->count() > 0){
                //如果该游戏是在游戏平台中,那么在该游戏平台列表中的游戏洗码是无效的;
                \WLog::info('该投注流水有游戏平台不享受洗码');
                $gamePlatsId = $withdrawFlowLimitWithGameLimit->limitGamePlats->each(function(PlayerWithdrawFlowLimitLogGamePlat $element){
                    \WLog::info('游戏平台 id:'.$element->def_game_plat_id);
                    return $element->def_game_plat_id;
                })->toArray();
                if(in_array($game->gamePlat->game_plat_id,$gamePlatsId)){
                    return false;
                }
            }
            //如果没有限游戏平台,则都不享受洗码
            else{
                \WLog::info('该会员有参与活动,但该活动不支持洗码');
                return false;
            }
        }
        return true;
    }
}
