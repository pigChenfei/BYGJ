<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Models\CarrierAgentUser;
use App\Models\Conf\CarrierRebateFinancialFlowSubordinate;
use App\Models\Conf\CarrierSubordinateAgentCommission;
use App\Models\Player;
use App\Repositories\Agent\AgentUserRepository;
use App\Repositories\Agent\PlayerRepository;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class AgentOpenAccountController extends AppBaseController
{
    
    /**
     * Display a listing of the CarrierAgentUser.
     *
     *
     * @return Response
     */
    public function index()
    {
        //1.当前代理是否支付多级代理
        $agent = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('agentLevel.commissionAgentConf')->first();

        //3.判断代理类型
        if ($agent->isCommissionAgent()){
            //如果是佣金代理,判断当前佣金代理级别
            $commissionAgentLevel = CarrierAgentUser::getAgentLevel($agent->parent_id);
            if ($commissionAgentLevel != 1){
                $agentCommissionConf = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('subordinateAgentCommission')->first();
                $agentCommission = $agentCommissionConf->subordinateAgentCommission->commission_ratio;
            }else{
                $agentCommission = $agent->agentLevel->commissionAgentConf->sub_commission_ratio;
            }
        }
        //TODO 洗码&占成设置
        //如果是洗码代理
        elseif($agent->agentLevel->isRebateFinancialFlowAgent()){
            //判断当前洗码代理级别
            $washCodeAgentLevel = CarrierAgentUser::getAgentLevel($agent->parent_id);
            if ($washCodeAgentLevel != 1){
                $washCodeAgent = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('rebateFinancialFlowSubordinate.gamePlat')->first();
                $agentWashCode = $washCodeAgent->rebateFinancialFlowSubordinate;
            }else{
                $washCodeAgent = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('agentLevel.rebateFinancialFlowAgentGamePlatConf.gamePlat')->first();
                $agentWashCode = $washCodeAgent->agentLevel->rebateFinancialFlowAgentGamePlatConf;
            }
       }


        if ($agent->agentLevel->is_multi_agent == 1){
            //2.判断当前代理是几级代理
            $agentLevel = CarrierAgentUser::getAgentLevel($agent->parent_id);
        }else{
            $agentLevel = 5;
        }

       if(isset($agentCommission)){

           return view('Agent.agent_open_account.index')->with(['agent'=>$agent,'agentLevel'=>$agentLevel,'agentCommission'=>$agentCommission]);
       }elseif (isset($agentWashCode)){
           return view('Agent.agent_open_account.index')->with(['washCodeAgent'=>$washCodeAgent,'agentLevel'=>$agentLevel,'agentWashCode'=>$agentWashCode]);
       }

    }


    public function createAgent(Agent\CreateCarrierAgentUserRequest $request,CarrierAgentUserRepository $agentUserRepository){

        $input = $request->all();

        if(\WinwinAuth::agentUser()->agentLoginConf->is_allow_player_register == 0) {
            return $this->sendErrorResponse('注册系统升级中!',403);
        }

        //运营商ID
        $input['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        //生成随机邀请码
        $input['promotion_code'] = CarrierAgentUser::generateReferralCode();
        //默认取款密码000000
        $input['pay_password'] = bcrypt('000000');

        $input['password'] = bcrypt($request->get('password'));
        if(isset($input['commission_ratio'])){
            $agentCommission['commission_ratio'] = array_pull($input,'commission_ratio');
            $agentCommission['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        }elseif(isset($input['agent_rebate_financial_flow_rate']) && isset($input['game_plat_id'])){
            $agentWashCode['agent_rebate_financial_flow_rate'] = array_pull($input,'agent_rebate_financial_flow_rate');
            $agentWashCode['carrier_game_plat_id'] = array_pull($input,'game_plat_id');
            $agentWashCode['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        }

      if (isset($agentCommission)){
          try{
              \DB::transaction(function () use ($input, $agentUserRepository,$agentCommission) {
                  $agent = $agentUserRepository->create($input);
                  if ($agent){
                      $agentCommission['agent_id'] = $agent->id;
                      $subordinateAgentCommission = new CarrierSubordinateAgentCommission();
                      $subordinateAgentCommission->create($agentCommission);
                  }
              });
              return $this->sendSuccessResponse(route('agentOpenAccounts.index'));
          }catch(\Exception $e){
              return $this->sendErrorResponse('注册失败', 403);
          }
      }elseif (isset($agentWashCode)){
          try{
              \DB::transaction(function () use ($input, $agentUserRepository,$agentWashCode) {
                  $agent = $agentUserRepository->create($input);
                  if ($agent){
                      $agentWashCode['agent_id'] = $agent->id;

                      $i = 0;
                      $array1 = array();
                      foreach ($agentWashCode['carrier_game_plat_id'] as $item){
                          $array1[$i] = ['carrier_id' => $agentWashCode['carrier_id'],'agent_id' => $agentWashCode['agent_id'],'carrier_game_plat_id' => $item];
                          $i++;
                      }

                      $j = 0;
                      foreach ($agentWashCode['agent_rebate_financial_flow_rate'] as $item){
                          $array2[$j] = ['agent_rebate_financial_flow_rate' => $item];
                          $j++;
                      }

                      $count = count($array1);
                      for($k=0;$k<$count;$k++){
                          $array[$k] = array_merge($array1[$k],$array2[$k]);
                      }

                     DB::table('conf_carrier_rebate_financial_flow_subordinate_agent')->insert(
                         $array
                     );
                  }
              });
              return $this->sendSuccessResponse(route('agentOpenAccounts.index'));
          }catch(\Exception $e){
              return $this->sendErrorResponse('注册失败', 403);
          }
      }
    }

    public function createPlayer(Agent\CreatePlayerRequest $request,PlayerRepository $playerRepository){

        $input = $request->all();

        if(\WinwinAuth::agentUser()->agentLoginConf->is_allow_player_register == 0) {
            return $this->sendErrorResponse('注册系统升级中!',403);
        }

        $input['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        //生成随机邀请码
        $input['referral_code'] = Player::generateReferralCode();
        //默认取款密码000000
        $input['pay_password'] = bcrypt('000000');
        $input['password'] = bcrypt($request->get('password'));

        $agent = CarrierAgentUser::where('id',$request->get('agent_id'))->with('agentLevel')->first();
        if ($agent){
            $input['player_level_id'] = $agent->agentLevel->default_player_level;
        }else{
            return $this->sendErrorResponse('账号异常,请稍后再试!',403);
        }

        $player = $playerRepository->create($input);

        if ($player){
          return $this->sendSuccessResponse(route('agentOpenAccounts.index'));
        }else{
            return $this->sendNotFoundResponse();
        }

    }
    
}
