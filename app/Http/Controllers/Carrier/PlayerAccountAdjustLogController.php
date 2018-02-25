<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerAccountAdjustLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerAccountAdjustLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerAccountAdjustLogRequest;
use App\Models\CarrierPayChannel;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountAdjustLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Models\Map\CarrierGamePlat;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Repositories\Carrier\PlayerAccountAdjustLogRepository;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class PlayerAccountAdjustLogController extends AppBaseController
{
    /** @var  PlayerAccountAdjustLogRepository */
    private $playerAccountAdjustLogRepository;

    public function __construct(PlayerAccountAdjustLogRepository $playerAccountAdjustLogRepo)
    {
        $this->playerAccountAdjustLogRepository = $playerAccountAdjustLogRepo;
    }

    /**
     * Display a listing of the PlayerAccountAdjustLog.
     *
     * @param PlayerAccountAdjustLogDataTable $playerAccountAdjustLogDataTable
     * @return Response
     */
    public function index(PlayerAccountAdjustLogDataTable $playerAccountAdjustLogDataTable)
    {
        return $playerAccountAdjustLogDataTable->render('Carrier.player_account_adjust_logs.index');
    }

    /**
     * Show the form for creating a new PlayerAccountAdjustLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_account_adjust_logs.create');
    }

    /**
     * Store a newly created PlayerAccountAdjustLog in storage.
     *
     * @param CreatePlayerAccountAdjustLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerAccountAdjustLogRequest $request)
    {
        //调整会员余额
        //1, 如果需要记账, 调整运营商银行卡余额, 新增运营商资金流水
        //2, 调整运营商额度
        //3, 如果有限流水平台,新增限制流水平台数据,同时需要将非该平台的游戏账户禁止转账,防止会员在这些游戏账户之间互相转账,禁止转账不能直接设置is_lock,应该直接通过流水限制游戏平台判断
        //4, 如果有设置那么清空之前的流水限制记录
        //5, 更新会员账户余额
        if($request->get('amount') == 0){
            return $this->sendErrorResponse("请设置调整额度");
        }
        try{
            \DB::transaction(function () use ($request){
                $input = $request->all();
                $isResetWithdrawFlowLimit = $request->get('is_reset_bet_flow_limit',false) ? true : false;
                $limitFlow = new PlayerWithdrawFlowLimitLog();
                $player = Player::findOrFail($input['player_id']);
                $playerAccountLog = new PlayerAccountLog();
                $limitFlow->limit_amount = $input['withdraw_limit_amount'];
                $limitFlow->player_id    = $input['player_id'];
                $limitFlow->carrier_id   = \WinwinAuth::carrierUser()->carrier_id;
                $carrierConsumptionLog = new CarrierQuotaConsumptionLog();
                //检测调整类型
                if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_DEPOSIT){
                    $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_ADJUST_REMAIN_ACCOUNT;
                    $carrierConsumptionLog->consumption_source = '调整会员余额';
                    $playerAccountLog->fund_type = $request->get('adjust_is_plus') == 1 ? PlayerAccountLog::FUND_TYPE_DEPOSIT : PlayerAccountLog::FUND_TYPE_WITHDRAW;
                    $playerAccountLog->fund_source = '客服调整余额';
                }else if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_REBATE_FINANCIAL_FLOW){
                    $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_ADJUST_REBATE_FINANCIAL_FLOW;
                    $carrierConsumptionLog->consumption_source = '调整会员洗码';
                    $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW;
                    $playerAccountLog->fund_source = '客服调整洗码';
                }else if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_BONUS){
                    $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_ADJUST_BONUS;
                    $carrierConsumptionLog->consumption_source = '调整会员红利';
                    $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_BONUS;
                    $playerAccountLog->fund_source = '客服调整红利';
                }
                //调整金额
                $input['amount'] = $request->get('amount') * ($request->get('adjust_is_plus') == 1 ? 1 : -1);
                //记账处理
                if($recordPayChannel = $request->get('amount_record_pay_channel')){
                    $payChannel = CarrierPayChannel::available()->findOrFail($recordPayChannel);
                    $payChannel->balance += $input['amount'];
                    if($payChannel->balance < 0){
                        throw new \Exception('该银行卡余额不足');
                    }
                    $carrierConsumptionLog->amount = $input['amount'];
                    $carrierConsumptionLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $carrierConsumptionLog->pay_channel_remain_amount = $payChannel->balance;
                    $carrierConsumptionLog->related_pay_channel = $payChannel->id;
                    $carrierConsumptionLog->save();
                    $payChannel->update();
                }
                //调整运营商额度;
//                $carrier  = \WinwinAuth::carrierUser()->carrier;
//                $carrier->remain_quota += $input['amount'];
//                $carrier->update();
                //检测是否清空之前的流水限制
                if($isResetWithdrawFlowLimit){
                     PlayerWithdrawFlowLimitLog::where('created_at','<=',Carbon::now())->update([
                         'is_finished' => true,
                         'operator_id' => \WinwinAuth::carrierUser()->id,
                     ]);
                }
                //处理人员
                $input['operator'] = \WinwinAuth::carrierUser()->id;
                $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
                $adjustLog= $this->playerAccountAdjustLogRepository->create($input);
                //新增账户记录
                $playerAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                $playerAccountLog->player_id  = $input['player_id'];
                $playerAccountLog->amount     = abs($input['amount']);
                $playerAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
                $limitFlow->player_account_log = $adjustLog->id;
                $limitFlow->limit_amount > 0 && $limitFlow->save();
                //更新会员主账户余额
                $oldMainAmount = $player->main_account_amount;
                $player->main_account_amount += $input['amount'];
                $playerAccountLog->remark = '主账户原余额： '.$oldMainAmount.' 现余额： '.$player->main_account_amount;
                $playerAccountLog->save();
                $player->update();
                //处理限制流水游戏平台
                if($betFlowLimitGamePlats = $request->get('bet_flow_game_plats')){
                    foreach ($betFlowLimitGamePlats as $betFlowLimitGamePlat){
                        $limitGamePlat = new PlayerWithdrawFlowLimitLogGamePlat();
                        $limitGamePlat->def_game_plat_id = $betFlowLimitGamePlat;
                        $limitGamePlat->player_withdraw_flow_limit_id = $limitFlow->id;
                        $limitGamePlat->save();
                    }
                }
            });
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified PlayerAccountAdjustLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerAccountAdjustLog = $this->playerAccountAdjustLogRepository->findWithoutFail($id);
        if (empty($playerAccountAdjustLog)) {
            return redirect(route('playerAccountAdjustLogs.index'));
        }
        return view('Carrier.player_account_adjust_logs.show')->with('playerAccountAdjustLog', $playerAccountAdjustLog);
    }

    /**
     * Show the form for editing the specified PlayerAccountAdjustLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerAccountAdjustLog = $this->playerAccountAdjustLogRepository->findWithoutFail($id);
        if (empty($playerAccountAdjustLog)) {
            return redirect(route('playerAccountAdjustLogs.index'));
        }
        return view('Carrier.player_account_adjust_logs.edit')->with('playerAccountAdjustLog', $playerAccountAdjustLog);
    }

    /**
     * Update the specified PlayerAccountAdjustLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerAccountAdjustLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerAccountAdjustLogRequest $request)
    {
        $playerAccountAdjustLog = $this->playerAccountAdjustLogRepository->findWithoutFail($id);
        if (empty($playerAccountAdjustLog)) {
            return redirect(route('playerAccountAdjustLogs.index'));
        }
        $this->playerAccountAdjustLogRepository->update($request->all(), $id);
        return redirect(route('playerAccountAdjustLogs.index'));
    }

    /**
     * Remove the specified PlayerAccountAdjustLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerAccountAdjustLog = $this->playerAccountAdjustLogRepository->findWithoutFail($id);
        if (empty($playerAccountAdjustLog)) {
            return redirect(route('playerAccountAdjustLogs.index'));
        }
        $this->playerAccountAdjustLogRepository->delete($id);
        return redirect(route('playerAccountAdjustLogs.index'));
    }

    /**
     * 批量调整会员余额
     * @return $this
     */
    public function passPlayerAccountEdit()
    {
        $players = Player::where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierPayChannel =  CarrierPayChannel::with('payChannel.payChannelType')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierGamePlat = CarrierGamePlat::with('gamePlat')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        return view('Carrier.player_account_adjust_logs.pass_player_account_edit')->with(['players'=>$players,'carrierPayChannel'=>$carrierPayChannel,'carrierGamePlat'=>$carrierGamePlat]);
    }

    /**
     * 批量调整会员红利
     * @return $this
     */
    public function hAccountEdit()
    {
        $players = Player::where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierPayChannel =  CarrierPayChannel::with('payChannel.payChannelType')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierGamePlat = CarrierGamePlat::with('gamePlat')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        return view('Carrier.player_account_adjust_logs.h_account_edit')->with(['players'=>$players,'carrierPayChannel'=>$carrierPayChannel,'carrierGamePlat'=>$carrierGamePlat]);
    }

        /**
     * 批量调整会员洗码
     * @return $this
     */
    public function xAccountEdit()
    {
        $players = Player::where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierPayChannel =  CarrierPayChannel::with('payChannel.payChannelType')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $carrierGamePlat = CarrierGamePlat::with('gamePlat')->where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        return view('Carrier.player_account_adjust_logs.x_account_edit')->with(['players'=>$players,'carrierPayChannel'=>$carrierPayChannel,'carrierGamePlat'=>$carrierGamePlat]);
    }


    public function savePassPlayerAccount(Request $request)
    {
        //调整会员余额
        //1, 如果需要记账, 调整运营商银行卡余额, 新增运营商资金流水
        //2, 调整运营商额度
        //3, 如果有限流水平台,新增限制流水平台数据,同时需要将非该平台的游戏账户禁止转账,防止会员在这些游戏账户之间互相转账,禁止转账不能直接设置is_lock,应该直接通过流水限制游戏平台判断
        //4, 如果有设置那么清空之前的流水限制记录
        //5, 更新会员账户余额
        if($request->get('amount') == 0){
            return $this->sendErrorResponse("请设置调整额度");
        }
        //会员用户
        if($playerUserIdJsonData = $request->get('player_user_id_json')){
            $playerUserArray = json_decode($playerUserIdJsonData,true);
            $playerUserArray = array_filter($playerUserArray,function ($element){
                return $element['selectedPlayerPages'] && is_array($element['selectedPlayerPages']);
            });
            foreach ($playerUserArray as $playerUser){
                foreach ($playerUser['selectedPlayerPages'] as $key => $value) {
                        $isResetWithdrawFlowLimit = $request->get('is_reset_bet_flow_limit',false) ? true : false;
                        $limitFlow = new PlayerWithdrawFlowLimitLog();
                        $player = Player::findOrFail($value);
                        $playerAccountLog = new PlayerAccountLog();
                        $limitFlow->limit_amount = $request->get('withdraw_limit_amount');
                        $limitFlow->player_id    = $value;
                        $limitFlow->carrier_id   = \WinwinAuth::carrierUser()->carrier_id;
                        $carrierConsumptionLog = new CarrierQuotaConsumptionLog();
                        //检测调整类型
                        if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_DEPOSIT){
                            $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_ADJUST_REMAIN_ACCOUNT;
                            $carrierConsumptionLog->consumption_source = '调整会员余额';
                            $playerAccountLog->fund_type = $request->get('adjust_is_plus') == 1 ? PlayerAccountLog::FUND_TYPE_DEPOSIT : PlayerAccountLog::FUND_TYPE_WITHDRAW;
                            $playerAccountLog->fund_source = '客服调整余额';
                        }
                        else if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_BONUS)
                        {
                            $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_ADJUST_BONUS;
                            $carrierConsumptionLog->consumption_source = '调整会员红利';
                            $playerAccountLog->fund_type = 3;
                            $playerAccountLog->fund_source = '客服调整红利';
                        }
                        else if($request->get('adjust_type') == PlayerAccountAdjustLog::ADJUST_TYPE_REBATE_FINANCIAL_FLOW)
                        {
                            $limitFlow->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_MANUAL_REBATE_FINANCIAL_FLOW;
                            $carrierConsumptionLog->consumption_source = '调整会员洗码';
                            $playerAccountLog->fund_type = 4;
                            $playerAccountLog->fund_source = '客服调整洗码';
                        }
                        //调整金额
                        $input['amount'] = $request->get('amount') * ($request->get('adjust_is_plus') == 1 ? 1 : -1);
                        //记账处理
                        if($recordPayChannel = $request->get('amount_record_pay_channel')){
                            $payChannel = CarrierPayChannel::available()->findOrFail($recordPayChannel);
                            $payChannel->balance += $input['amount'];
                            if($payChannel->balance < 0){
                                throw new \Exception('该银行卡余额不足');
                            }
                            $carrierConsumptionLog->amount = $input['amount'];
                            $carrierConsumptionLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                            $carrierConsumptionLog->pay_channel_remain_amount = $payChannel->balance;
                            $carrierConsumptionLog->related_pay_channel = $payChannel->id;
                            $carrierConsumptionLog->save();
                            $payChannel->update();
                        }

                        //检测是否清空之前的流水限制
                        if($isResetWithdrawFlowLimit){
                            PlayerWithdrawFlowLimitLog::where('created_at','<=',Carbon::now())->update([
                                'is_finished' => true,
                                'operator_id' => \WinwinAuth::carrierUser()->id,
                            ]);
                        }
                        //处理人员
                        $input['operator'] = \WinwinAuth::carrierUser()->id;
                        $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
                        $input['player_id'] = $value;
                        $input['adjust_type'] = $request->get('adjust_type');
                        $adjustLog= $this->playerAccountAdjustLogRepository->create($input);
                        //新增账户记录
                        $playerAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $playerAccountLog->player_id  = $value;
                        $playerAccountLog->amount     = abs($input['amount']);
                        $playerAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
                        $limitFlow->player_account_log = $adjustLog->id;
                        $limitFlow->limit_amount > 0 && $limitFlow->save();
                        //更新会员主账户余额
                        $oldMainAmount = $player->main_account_amount;
                        $player->main_account_amount += $input['amount'];
                        $playerAccountLog->remark = '主账户原余额： '.$oldMainAmount.' 现余额： '.$player->main_account_amount;
                        $playerAccountLog->save();
                        $player->update();
                        //处理限制流水游戏平台
                        if($betFlowLimitGamePlats = $request->get('bet_flow_game_plats')){
                            foreach ($betFlowLimitGamePlats as $betFlowLimitGamePlat){
                                $limitGamePlat = new PlayerWithdrawFlowLimitLogGamePlat();
                                $limitGamePlat->def_game_plat_id = $betFlowLimitGamePlat;
                                $limitGamePlat->player_withdraw_flow_limit_id = $limitFlow->id;
                                $limitGamePlat->save();
                            }
                        }
                }
                return $this->sendSuccessResponse( route('carrierActivities.index'));
            }
        }else{
            return $this->sendErrorResponse("请选择会员");
        }
    }
}
