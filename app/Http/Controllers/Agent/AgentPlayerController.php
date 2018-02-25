<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Models\Conf\RebateFinancialFlowAgentGamePlatConf;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Map\CarrierGamePlat;
use App\Models\Player;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentPlayerDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class AgentPlayerController extends AppBaseController
{
    /** @var  AgentUserRepository */
    private $agentUserRepository;

    public function __construct(AgentUserRepository $agentUserRepo)
    {
        $this->agentUserRepository = $agentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param AgentPlayerDataTable $agentPlayerDataTable
     * @return Response
     */
    public function index(AgentPlayerDataTable $agentPlayerDataTable)
    {

        $agentPlayer = Player::with(['betFlowLogs','accountLogs','depositLogs' => function($query){
            $query->payedSuccessfully();
        }])->select("*")->where(['agent_id'=>\WinwinAuth::agentUser()->id]);



        $agentPlayer = $agentPlayer->orderBy('created_at', 'desc')->paginate(10);

        $agentPlayer->map(function(Player $player){
            //总存款
            $player->deposit_total = $player->depositLogs->map(function(PlayerDepositPayLog $log){
                return $log->amount;
            })->reduce(function($pre,$next){ return $pre + $next; });
            //总取款
            $player->withdraw_total = $player->accountLogs->map(function(PlayerAccountLog $log){
                if($log->fund_type == PlayerAccountLog::FUND_TYPE_WITHDRAW){
                    return $log->amount;
                }
                return 0.00;
            })->reduce(function($pre,$next){ return $pre + $next; });
            //总优惠
            $player->depositBenefitAmount = $player->depositLogs->map(function(PlayerDepositPayLog $log){
                return $log->benefit_amount;
            })->reduce(function($pre,$next){ return $pre + $next; });
            //总投注额
            $player->betFlowAll = $player->betFlowLogs->map(function(PlayerBetFlowLog $log){
                return $log->bet_amount;
            })->reduce(function($pre,$next){ return $pre + $next; });
            //总有效投注额
            $player->betFlowAvailable = $player->betFlowLogs->map(function(PlayerBetFlowLog $log){
                if($log->bet_flow_available == PlayerBetFlowLog::BET_FLOW_AVAILABLE){
                    return $log->available_bet_amount;
                }
                return 0.00;
            })->reduce(function($pre,$next){ return $pre + $next; });
            //总洗码
            $player->rebateFinancialFlowAmount = $player->accountLogs->map(function(PlayerAccountLog $log){
                if($log->fund_type == PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW){
                    return $log->amount;
                }
                return 0.00;
            })->reduce(function($pre,$next){ return $pre + $next; });

            //公司总输赢
            $player->winlose_total = $player->betFlowLogs->map(function(PlayerBetFlowLog $log){
                if($log->bet_flow_available == PlayerBetFlowLog::BET_FLOW_AVAILABLE){
                    return $log->company_win_amount;
                }
                return 0.00;
            })->reduce(function($pre,$next){ return $pre + $next; });
        });
//        dump($agentPlayer);

        if (\WinwinAuth::agentUser()->template_agent_admin == 'Agent'){
            return $agentPlayerDataTable->render('Agent.agent_player.index');
        } elseif (\WinwinAuth::agentUser()->template_agent_admin == 'Template_Agent_Admin_One'){
            return view('Template_Agent_Admin_One.agent_player.index', compact('agentPlayer'));
        } else {
            return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_player.index', compact('agentPlayer'));
        }
        return false;

    }
    //洗码设置
    public function rebate()
    {
        //查找运营商分配平台表与代理设置比例中的游戏平台差异 添加或删除设置比例记录
        $agent_game_ids = RebateFinancialFlowAgentGamePlatConf::where('agent_id',\WinwinAuth::agentUser()->id)->pluck('carrier_game_plat_id')->toArray();
        $carrier_game_ids = CarrierGamePlat::where('status', 1)->pluck('game_plat_id')->toArray();
        $diff = array_diff($agent_game_ids, $carrier_game_ids);
        $comm = array_diff($carrier_game_ids, $agent_game_ids);
        if ($diff){
            RebateFinancialFlowAgentGamePlatConf::where('agent_id',\WinwinAuth::agentUser()->id)->whereIn('carrier_game_plat_id',$diff)->delete();
        }
        if ($comm){
            foreach($comm as $v){
                $new = new RebateFinancialFlowAgentGamePlatConf();
                $new->carrier_id = \WinwinAuth::agentUser()->carrier_id;
                $new->agent_id = \WinwinAuth::agentUser()->id;
                $new->carrier_game_plat_id = $v;
                $new->save();
            }
        }
        $agentpa = RebateFinancialFlowAgentGamePlatConf::where('agent_id',\WinwinAuth::agentUser()->id)->whereHas("carrierGamePlat.gamePlat")
            ->with("carrierGamePlat.gamePlat")
            ->get();
        return view('Template_Agent_Admin_One.agent_player.rebate', compact('agentpa'));

    }
    //保存洗码设置
    public function saveRebate(Request $request)
    {
        foreach ($_POST['setid'] as $k => $v) {
            $ar[] = array(
                $v,
                $_POST['player_rebate_financial_flow_rate'][$k],
                $_POST['player_rebate_financial_flow_max_amount'][$k],
            );
        }
        \DB::beginTransaction();
        try{
            foreach ($ar as $key => $value) {
                if ($value[1] > 100 || !is_numeric($value[1]) || !is_numeric($value[2])) {
                    $error_respon = array(
                        'success' => false,
                        'message' => '参数错误，请重试'
                    );
                    return $this->sendErrorResponse($error_respon, 404);
                }

                $data['player_rebate_financial_flow_rate'] = $value[1];
                $data['player_rebate_financial_flow_max_amount'] = $value[2];
                RebateFinancialFlowAgentGamePlatConf::where('id', $value[0])->update($data);
            }
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            \Log::error('会员洗码比例设置失败：',['message' => $e->getMessage()]);
            return $this->sendErrorResponse('会员洗码比例设置失败，请重试', 404);
        }
        if ($request->ajax()) {
            return $this->sendSuccessResponse();
        }
        return $this->sendSuccessResponse();
    }
}
