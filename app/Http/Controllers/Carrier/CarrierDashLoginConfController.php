<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierDashLoginConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierDashLoginConfRequest;
use App\Models\Conf\CarrierDashLoginConf;
use App\Repositories\Carrier\CarrierDashLoginConfRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Carrier\CarrierDepositConfRepository;
use App\Repositories\Carrier\CarrierPasswordRecoverySiteConfRepository;
use App\Repositories\Carrier\CarrierRegisterBasicConfRepository;
use App\Repositories\Carrier\CarrierWithdrawConfRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierDashLoginConfController extends AppBaseController
{
    /** @var  CarrierDashLoginConfRepository */
    private $carrierDashLoginConfRepository;

    public function __construct(CarrierDashLoginConfRepository $carrierDashLoginConfRepo)
    {
        $this->carrierDashLoginConfRepository = $carrierDashLoginConfRepo;
    }

    /**
     * Display a listing of the CarrierDashLoginConf.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request,CarrierDepositConfRepository $carrierDepositRepo,CarrierWithdrawConfRepository $carrierWithdrawConfRepo,CarrierPasswordRecoverySiteConfRepository $carrierPasswordRecoverySiteConfRepo)
    {

        $this->carrierDashLoginConfRepository->pushCriteria(new RequestCriteria($request));
        $carrierDashLoginConf = $this->carrierDashLoginConfRepository->first();
        $arr = $carrierDashLoginConf->toArray();


        //dd($arr);
        $arr1 = array_except($arr,['id','carrier_id','updated_at','created_at','forbidden_login_comment','carrier_login_failed_count_when_locked','player_login_failed_count_when_locked','player_register_forbidden_user_names','player_forbidden_login_comment','player_forbidden_register_comment','is_allow_agent_login','is_allow_agent_register','agent_login_failed_count_when_locked','agent_register_forbidden_user_names','agent_forbidden_login_comment','agent_forbidden_register_comment','is_allow_agent_withdraw_with_password','is_allow_player_login','is_allow_player_register','is_check_exists_real_user_name','is_allow_user_withdraw_with_password']);
        $players = array_except($arr1,['agent_type_conf_status','agent_realname_conf_status','agent_birthday_conf_status','agent_email_conf_status','agent_phone_conf_status','agent_qq_conf_status','agent_skype_conf_status','agent_wechat_conf_status','agent_promotion_mode_conf_status','agent_promotion_url_conf_status','agent_promotion_idea_conf_status']);
        $agent = array_except($arr1,['player_birthday_conf_status','player_realname_conf_status','player_email_conf_status','player_phone_conf_status','player_qq_conf_status','player_wechat_conf_status','player_consignee_conf_status','player_receiving_address_conf_status']);

        $playerstatus = array_except($arr,['id','carrier_id','updated_at','created_at','agent_type_conf_status','agent_realname_conf_status','agent_birthday_conf_status','agent_email_conf_status','agent_phone_conf_status','agent_qq_conf_status','agent_skype_conf_status','agent_wechat_conf_status','agent_promotion_mode_conf_status','agent_promotion_url_conf_status','agent_promotion_idea_conf_status','player_birthday_conf_status','player_realname_conf_status','player_email_conf_status','player_phone_conf_status','player_qq_conf_status','player_wechat_conf_status','player_consignee_conf_status','player_receiving_address_conf_status','forbidden_login_comment','carrier_login_failed_count_when_locked','player_login_failed_count_when_locked','player_register_forbidden_user_names','player_forbidden_login_comment','player_forbidden_register_comment','is_allow_agent_login','is_allow_agent_register','agent_login_failed_count_when_locked','agent_register_forbidden_user_names','agent_forbidden_login_comment','agent_forbidden_register_comment','is_allow_agent_withdraw_with_password']);

        $agentstatus = array_except($arr,['id','carrier_id','updated_at','created_at','agent_type_conf_status','agent_realname_conf_status','agent_birthday_conf_status','agent_email_conf_status','agent_phone_conf_status','agent_qq_conf_status','agent_skype_conf_status','agent_wechat_conf_status','agent_promotion_mode_conf_status','agent_promotion_url_conf_status','agent_promotion_idea_conf_status','player_birthday_conf_status','player_realname_conf_status','player_email_conf_status','player_phone_conf_status','player_qq_conf_status','player_wechat_conf_status','player_consignee_conf_status','player_receiving_address_conf_status','forbidden_login_comment','carrier_login_failed_count_when_locked','player_login_failed_count_when_locked','player_register_forbidden_user_names','player_forbidden_login_comment','player_forbidden_register_comment','agent_login_failed_count_when_locked','agent_register_forbidden_user_names','agent_forbidden_login_comment','agent_forbidden_register_comment','is_allow_player_login','is_allow_player_register','is_check_exists_real_user_name','is_allow_user_withdraw_with_password']);

        $carrierDepositConf = $carrierDepositRepo->first();
        $carrierWithdrawConf = $carrierWithdrawConfRepo->first();
        $carrierPasswordRecoverySiteConf = $carrierPasswordRecoverySiteConfRepo->first();

        $deposit = array_except($carrierDepositConf->toArray(),['id','carrier_id','updated_at','created_at','unreview_deposit_record_limit']);
        $Withdraw = array_except($carrierWithdrawConf->toArray(),['id','carrier_id','updated_at','created_at','player_day_withdraw_max_sum','player_once_withdraw_max_sum','agent_day_withdraw_success_limit_count','agent_day_withdraw_max_sum','agent_once_withdraw_max_sum','agent_once_withdraw_min_sum','player_day_withdraw_success_limit_count','player_once_withdraw_min_sum']);


        $playerWithdraw = array_except($Withdraw,['is_allow_agent_withdraw','is_allow_agent_withdraw_decimal']);
        $agentWithdraw = array_except($Withdraw,['is_allow_player_withdraw','is_allow_player_withdraw_decimal','is_diaplay_flow_water_check','is_check_flow_water_when_withdraw']);

        if(empty($carrierDashLoginConf)){
            $carrierDashLoginConf = new CarrierDashLoginConf();
            $carrierDashLoginConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $carrierDashLoginConf->save();
        }

        return view('Carrier.carrier_dash_login_confs.index')
            ->with(['carrierDashLoginConfs'=> $carrierDashLoginConf,'carrierDepositConfs'=>$carrierDepositConf,'carrierWithdrawConfs'=>$carrierWithdrawConf,'carrierPasswordRecoverySiteConfs'=>$carrierPasswordRecoverySiteConf,'players'=>$players,'agents'=>$agent,'playerstatus'=>$playerstatus,'agentstatus'=>$agentstatus,'deposit'=>$deposit,'playerWithdraw'=>$playerWithdraw,'agentWithdraw'=>$agentWithdraw]);
    }

    /**
     * Show the form for creating a new CarrierDashLoginConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierDashLoginConf in storage.
     *
     * @param CreateCarrierDashLoginConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierDashLoginConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierDashLoginConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Show the form for editing the specified CarrierDashLoginConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Update the specified CarrierDashLoginConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierDashLoginConfRequest $request
     *
     * @return Response
     */
    public function update($id = null, UpdateCarrierDashLoginConfRequest $request)
    {
        $input = $request->all();
        //处理checkbox配置项提交的值
        foreach ($input as $key => $value){
            if(is_array($value)){
                $input[$key] = array_reduce($value,function($pre,$next){
                    return $pre + $next;
                },0);
            }
        }

        $conf = CarrierDashLoginConf::first();
        $carrierDashLoginConf = $this->carrierDashLoginConfRepository->findWithoutFail($conf->id);

       if (array_has($input,'is_allow_player_login')){
           $arr = array_except($carrierDashLoginConf->toArray(),['id','carrier_id','forbidden_login_comment','carrier_login_failed_count_when_locked','is_allow_player_login','is_allow_player_register','player_login_failed_count_when_locked','player_login_failed_locked_time','player_register_forbidden_user_names','player_forbidden_login_comment','player_forbidden_register_comment','agent_login_failed_count_when_locked','agent_login_failed_locked_time','agent_register_forbidden_user_names','agent_forbidden_login_comment','agent_forbidden_register_comment','updated_at','created_at']);
           $result = array_diff_assoc($arr, $input);
           foreach ($result as $k => $v){
               if (!array_has($input,$k)){
                   $input[$k] = 0;
               }
           }
       }

        if (empty($carrierDashLoginConf)) {
            return $this->sendNotFoundResponse();
        }
        $update_type = $request->get('update_type');
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierDashLoginConfRepository->update($input, $conf->id);
            });
            return $this->sendSuccessResponse(route('carrierDashLoginConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }
    /**
     * Remove the specified CarrierDashLoginConf from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return $this->sendSuccessResponse([]);
    }
}
