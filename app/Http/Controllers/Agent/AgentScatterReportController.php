<?php

namespace App\Http\Controllers\Agent;

use App\DataTables\Agent\AgentScatterReportDataTable;
use App\Http\Requests\Carrier\CreateCarrierAgentSettleLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentSettleLogRequest;
use App\Models\Carrier;
use App\Models\CarrierAgentUser;
use App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo;
use App\Models\Log\CarrierAgentSettleLog;
use App\Models\Log\CarrierAgentSettlePeriodsLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Repositories\Carrier\CarrierAgentSettleLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\DataTables\Agent\AgentSettleReportDataTable;

class AgentScatterReportController extends AppBaseController
{
    /** @var  CarrierAgentCommissionSettleLogRepository */
    private $carrierAgentScatterLogRepository;

    public function __construct(CarrierAgentSettleLogRepository $carrierAgentSettleLogRepo)
    {
        $this->carrierAgentScatterLogRepository = $carrierAgentSettleLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentCommissionSettleLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(AgentScatterReportDataTable $agentScatterReportDataTable)
    {
        return $agentScatterReportDataTable->render('Agent.agent_scatter_reports.index');
    }

    /**
     * 查看详情
     * @param type $id
     */
    public function details($id)
    {
        //代理本身会员
        $agentSettlePeriodsLog = CarrierAgentSettlePeriodsLog::where('id',$id)->first();
        $start_time = $agentSettlePeriodsLog->start_time;
        $end_time = $agentSettlePeriodsLog->end_time;
        $betFlowSetting  = CarrierAgentUser::with('agentLevel.rebateFinancialFlowAgentGamePlatConf')->where('id',\WinwinAuth::agentUser()->id)->first();
        $agent_id = \WinwinAuth::agentUser()->id;
        $carrier_id = \WinwinAuth::agentUser()->carrier_id;
        //根据代理ID找出所有的所属玩家的ID
        $agentMember = Player::where('agent_id',$agent_id)->get();
        foreach ($agentMember as $item){
            $agentMemberID[] = $item->player_id;
        }


        //子代理会员
        $subAgentSettlePeriodsLog = CarrierAgentSettlePeriodsLog::where('agent_id',$id)->first();
        if ($subAgentSettlePeriodsLog){
            //根据代理ID找出所有的所属玩家的ID
            $subAgentMember = Player::where('agent_id',$subAgentSettlePeriodsLog->agent_id)->get();
            foreach ($agentMember as $item){
                $subAgentMemberID[] = $item->player_id;
            }
            //获得子代理下游戏平台所有投注额和有效投注额
            $subGamePlatBetFlow = PlayerBetFlowLog::select(DB::raw("`game_plat_id`,COUNT(player_id) as num,SUM(bet_amount) as bet_amount,SUM(available_bet_amount) as available_bet_amount"))
                //->where('bet_amount','>',$betFlowSetting->agentLevel->rebateFinancialFlowAgentBaseConf->available_member_monthly_bet_amount)
                ->whereIn('player_id',$subAgentMemberID)
                ->whereBetween('created_at',[$start_time,$end_time])
                ->groupBy('game_plat_id')
                ->get();


            $subAgentStatistics = ['num' => 0,'bet_amount' => 0,'available_bet_amount' =>0];
            foreach($subGamePlatBetFlow as $item){
                $subAgentStatistics['num'] +=  $item->num;
                $subAgentStatistics['bet_amount'] +=  $item->bet_amount;
                $subAgentStatistics['available_bet_amount'] +=  $item->available_bet_amount;
            }


            //总佣金
            $subAgentStatistics['agent_commission'] = 0;
            foreach ($subGamePlatBetFlow as $item){
                foreach ($betFlowSetting->agentLevel->rebateFinancialFlowAgentGamePlatConf as $item1){
                    if ($item->game_plat_id == $item1->carrier_game_plat_id){
                        $item->agent_rebate_financial_flow_rate = $item1->agent_rebate_financial_flow_rate;
                        $item->agent_commission = ($item->available_bet_amount * $item1->agent_rebate_financial_flow_rate);
                        $subAgentStatistics['agent_commission'] += $item->agent_commission;
                    }
                }
            }

        }else{
            $subAgentStatistics['agent_commission'] = 0;
        }


        //代理基本设置
        $agentBaseInfoConf = CarrierAgentUser::where('id',$agent_id)->with('agentLevel.rebateFinancialFlowAgentBaseConf')->first();

        //判断代理洗码比例是够跟随网站
        //TODO 会员是否跟随网站洗码优惠逻辑
        if ($agentBaseInfoConf->agentLevel->rebateFinancialFlowAgentBaseConf->is_player_rebate_financial_adapt_carrier_conf == CarrierRebateFinancialFlowAgentBaseInfo::PLAYER_REBATE_FINANCIAL_ADAPT_CARRIER_CONF_IS){

        }


        //有效会员判断
//       $effectivePlayer = PlayerBetFlowLog::whereIn('player_id',$agentMemberID)->get();


        //获得代理下游戏平台所有投注额和有效投注额
        $gamePlatBetFlow = PlayerBetFlowLog::select(DB::raw("`game_plat_id`,COUNT(player_id) as num,SUM(bet_amount) as bet_amount,SUM(available_bet_amount) as available_bet_amount"))
            //->where('bet_amount','>',$betFlowSetting->agentLevel->rebateFinancialFlowAgentBaseConf->available_member_monthly_bet_amount)
            ->whereIn('player_id',$agentMemberID)
            ->whereBetween('created_at',[$start_time,$end_time])
            ->groupBy('game_plat_id')
            ->get();


        $agentStatistics = ['num' => 0,'bet_amount' => 0,'available_bet_amount' =>0];
        foreach($gamePlatBetFlow as $item){
            $agentStatistics['num'] +=  $item->num;
            $agentStatistics['bet_amount'] +=  $item->bet_amount;
            $agentStatistics['available_bet_amount'] +=  $item->available_bet_amount;
        }


        //洗码佣金
        $agentStatistics['agent_commission'] = 0;
        foreach ($gamePlatBetFlow as $item){
            foreach ($betFlowSetting->agentLevel->rebateFinancialFlowAgentGamePlatConf as $item1){
               if ($item->game_plat_id == $item1->carrier_game_plat_id){
                   $item->agent_rebate_financial_flow_rate = $item1->agent_rebate_financial_flow_rate;
                   $item->agent_commission = ($item->available_bet_amount * $item1->agent_rebate_financial_flow_rate);
                   $agentStatistics['agent_commission'] += $item->agent_commission;
               }
            }
        }

        //子代理提成
        $agentStatistics['sub_agent_commission'] = 0.2 * $subAgentStatistics['agent_commission'];
        //总佣金
        $agentStatistics['commission'] = $agentStatistics['agent_commission'] + $agentStatistics['sub_agent_commission'];


        //当前运营商的所有游戏平台
        $carrierGamePlats = Carrier::where('id',$carrier_id)->with('mapGamePlats.gamePlat')->get();
        foreach ($carrierGamePlats as $item){
            if (head($item->mapGamePlats)){
               foreach ($item->mapGamePlats as $mapGamePlat){
                   $gamePlatName[$mapGamePlat->gamePlat->game_plat_id] = $mapGamePlat->gamePlat->game_plat_name;
               }
            }
        }


        return view('Agent.agent_scatter_reports.details')->with(['gamePlatBetFlow'=>$gamePlatBetFlow,'gamePlatName'=>$gamePlatName,'betFlowSetting'=> $betFlowSetting,'agentStatistics' => $agentStatistics]);
    }
    
}
