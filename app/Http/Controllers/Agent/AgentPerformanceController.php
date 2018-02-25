<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\AppBaseController;
use App\Models\CarrierAgentUser;
use App\Models\Def\GamePlat;
use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerLoginLog;
use App\Models\Map\CarrierGamePlat;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentPerformanceController extends AppBaseController
{

    public function index(Request $request){

        $start = $request->input('start_time', '');
        $end = $request->input('end_time', '');

        $agent_id = \WinwinAuth::agentUser()->id;

        //1.查询代理下玩家会员总人数
        $agentMemberSum = Player::AgentMemberSum($agent_id);
        if ($start){
            $agentMemberSum = $agentMemberSum->whereDate('created_at', '>=', $start);
        }
        if ($end){
            $agentMemberSum = $agentMemberSum->whereDate('created_at', '<=', $end);
        }
        $agentMemberSum = $agentMemberSum->count();
        //2.代理新增玩家会员人数
        $start_time = Carbon::now()->startOfDay();
        $end_time = Carbon::now()->endOfDay();
        if ($start){
            $start_time = $start;
        }
        if ($end){
            $end_time = $end;
        }
        $agentNewMemberSum = Player::AgentNewMemberSum($agent_id,$start_time,$end_time)->count();


        //3.活跃会员人数(当前时间减去上次登录时间不超过一个月)
        $now_time = Carbon::now();
        $last_month_time = Carbon::now()->subMonth();
        $activeMembers = Player::where('agent_id',$agent_id)->with(['loginLogs'=> function($query)use($last_month_time,$now_time){
            $query->LoginTime($last_month_time,$now_time);
        }])->get();

        $activeMember = 0;
        foreach ($activeMembers as $item){
            if (head($item->loginLogs)){
                $activeMember++;
            }
        }

        //4.代理推广点击
        $promoteClicks = CarrierAgentUser::where('id',$agent_id)->value('promotion_url_click_number');

        //5.首次存款次数&全部存款次数
        $depositNumbers = Player::where('agent_id',$agent_id)->withCount(['depositLogs' => function ($query)use($start, $end){

            if ($start){
                $query->whereDate('created_at', '>=', $start);
            }
            if ($end){
                $query->whereDate('created_at', '<=', $end);
            }
            $query->payedSuccessfully();
        }])->get();

        //全部存款次数
        $depositNumber = 0;
        //首次存款次数
        $firstDepositNumber = 0;
        foreach ($depositNumbers as $item){
            if ($item->deposit_logs_count > 1){
                $item->first_deposit_count = 1;
            }else{
                $item->first_deposit_count = $item->deposit_logs_count;
            }
            $firstDepositNumber += ($item->first_deposit_count);
            $depositNumber += ($item->deposit_logs_count);
        }

        //6.全部存款金额
        $depositAmounts = Player::where('agent_id',$agent_id)->with(['depositLogs' => function($query)use($start, $end){
            if ($start){
                $query->whereDate('created_at', '>=', $start);
            }
            if ($end){
                $query->whereDate('created_at', '<=', $end);
            }
            $query->payedSuccessfully();
        }])->get();

        $depositAmount = 0;
        foreach ($depositAmounts as $item){
            if (head($item->depositLogs)){
                $depositAmount += ($item->depositLogs->sum('amount'));
            }
        }


        //7.全部取款金额
        $withdrawAmounts = Player::where('agent_id',$agent_id)->with(['withdrawLogs' => function($query)use($start, $end){
            if ($start){
                $query->whereDate('created_at', '>=', $start);
            }
            if ($end){
                $query->whereDate('created_at', '<=', $end);
            }
            $query->AccountOut();
        }])->get();

        $withdrawAmount = 0;
        foreach ($withdrawAmounts as $item){
            if (head($item->withdrawLogs)){
                $withdrawAmount += ($item->withdrawLogs->sum('finally_withdraw_amount'));
            }
        }

        //8.获取平台数据
        $gamePlat = CarrierGamePlat::open()->pluck('game_plat_id');
        $mainPlat = MainGamePlat::active()->whereHas('gamePlats', function ($query)use($gamePlat){
            $query->where('status', 1)->whereIn('game_plat_id', $gamePlat);
        })->get();
        $bettingFlows = PlayerBetFlowLog::betFlowAvailable()->with(['player'])->whereHas('player', function ($query)use($agent_id){
            $query->where('agent_id',$agent_id);
        });
        if ($start){
            $bettingFlows = $bettingFlows->whereDate('created_at', '>=', $start);
        }
        if ($end){
            $bettingFlows = $bettingFlows->whereDate('created_at', '<=', $end);
        }
        $bettingFlows = $bettingFlows->get();
        $array = array();
        foreach ($mainPlat as $item){
            $games = CarrierGamePlat::open()->whereHas('gamePlat', function($query)use($item){
                $query->where('status', 1)->where('main_game_plat_id', $item->main_game_plat_id);
            })->pluck('game_plat_id');
            $betPlat = $companyPlat = $bettingFlows;
            $bettingAmount = $betPlat->whereIn('game_plat_id', $games)->sum('available_bet_amount');
            $companyWinAmount = $companyPlat->whereIn('game_plat_id', $games)->sum('company_win_amount');
            $array[] = array('plat'=>$item->main_game_plat_name,'bettingAmount'=>$bettingAmount,'companyWinAmount'=>$companyWinAmount);
        }

        $bettingAmountAll = $bettingFlows->sum('available_bet_amount');
        $companyWinAmountAll = $bettingFlows->sum('company_win_amount');


        if ($request->ajax()){
            return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_performance.table')->with([
                'agentMemberSum' => $agentMemberSum,
                'promoteClicks' => $promoteClicks,
                'agentNewMemberSum' => $agentNewMemberSum,
                'activeMember' => $activeMember,
                'depositNumber' => $depositNumber,
                'firstDepositNumber' => $firstDepositNumber,
                'depositAmount' => $depositAmount,
                'withdrawAmount' => $withdrawAmount,
                'array'=>$array,
                'bettingAmountAll'=>$bettingAmountAll,
                'companyWinAmountAll'=>$companyWinAmountAll,
            ]);
        }

        return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_performance.index')->with([
            'agentMemberSum' => $agentMemberSum,
            'promoteClicks' => $promoteClicks,
            'agentNewMemberSum' => $agentNewMemberSum,
            'activeMember' => $activeMember,
            'depositNumber' => $depositNumber,
            'firstDepositNumber' => $firstDepositNumber,
            'depositAmount' => $depositAmount,
            'withdrawAmount' => $withdrawAmount,
            'array'=>$array,
            'bettingAmountAll'=>$bettingAmountAll,
            'companyWinAmountAll'=>$companyWinAmountAll,
        ]);
    }

}
