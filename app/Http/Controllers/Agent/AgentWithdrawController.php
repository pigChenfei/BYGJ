<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Agent\CreateCarrierAgentUserRequest;
use App\Http\Requests\Carrier\CreateAgentBankCardRequest;
use App\Models\AgentBankCard;
use App\Models\CarrierAgentUser;
use App\Models\Conf\CarrierWithdrawConf;
use App\Models\Def\BankType;
use App\Models\Log\AgentWithdrawLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Models\PlayerBankCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class AgentWithdrawController extends AppBaseController
{

    //
    public function index(Request $request)
    {
        
        // 当前运营商下代理取款设置
        $agentWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::agentUser()->carrier_id)->first();
        
        // 当前代理所有的取款银行卡
        $agent_id = \WinwinAuth::agentUser()->id;
        $agentBankCard = AgentBankCard::where('agent_id', $agent_id)->with('bankType')->first();
        
        if ($agentBankCard) {
            // 处理银行卡号 *
            if ($agentBankCard->card_account) {
                $agentBankCard->card_account = substr_replace($agentBankCard->card_account, '***********', 4, 11);
            }
        }
        $banks = BankType::all();
        
        return view(\WinwinAuth::agentUser()->template_agent_admin . '.agent_withdraw.index')->with(compact('agentBankCard', 'agentWithdrawConf', 'banks'));
    }

    public function create()
    {
        // 查找默认银行取款渠道
        $banks = BankType::all();
        return view('Agent.agent_withdraw.create')->with('banks', $banks);
    }

    // 添加银行卡
    public function store(\App\Http\Requests\Agent\CreateAgentBankCardRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        $input['agent_id'] = \WinwinAuth::agentUser()->id;
        if ($input['card_owner_name'] != \WinwinAuth::agentUser()->realname) {
            return $this->sendErrorResponse('持卡人姓名与登录账号真实姓名不一致', 404);
        }
        
        $agentBankCard = new AgentBankCard();
        $result = $agentBankCard->create($input);
        if ($result) {
            return $this->sendSuccessResponse(route('agentWithdraws.index'));
        } else {
            return $this->sendErrorResponse('添加失败', 404);
        }
    }

    // 删除银行卡
    public function del()
    {
        $info = AgentBankCard::where([
            'carrier_id' => \WinwinAuth::agentUser()->carrier_id,
            'agent_id' => \WinwinAuth::agentUser()->id
        ])->first();
        if (! $info) {
            return $this->sendErrorResponse('参数错误，请重试', 404);
        }
        $result = $info->delete();
        if ($result) {
            return $this->sendSuccessResponse('操作成功');
        } else {
            return $this->sendErrorResponse('添加失败，请重试', 404);
        }
    }

    /**
     * 取款限额检查
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdrawQuotaCheck(Request $request)
    {
        // 当前运营商取款设置
        $carrierWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::agentUser()->carrier_id)->first();
        
        $apply_amount = $request->get('apply_amount');
        if ($apply_amount) {
            if ($apply_amount < $carrierWithdrawConf->agent_once_withdraw_min_sum) {
                return $this->sendErrorResponse('*单次取款最小限额为' . $carrierWithdrawConf->agent_once_withdraw_min_sum . '元', 404);
            } elseif (($apply_amount > \WinwinAuth::agentUser()->amount) && ($apply_amount < $carrierWithdrawConf->agent_once_withdraw_max_sum)) {
                return $this->sendErrorResponse('*账户余额不足', 404);
            } elseif ($apply_amount > $carrierWithdrawConf->agent_once_withdraw_max_sum) {
                return $this->sendErrorResponse('*单次取款最大限额为' . $carrierWithdrawConf->agent_once_withdraw_max_sum . '元', 404);
            } else {
                return $this->sendResponse([], '*注意：单次最低提款额为' . $carrierWithdrawConf->agent_once_withdraw_min_sum . '元,最高' . $carrierWithdrawConf->agent_once_withdraw_max_sum . '元');
            }
        } else {
            return $this->sendErrorResponse('*取款金额不能为空', 404);
        }
    }

    // 取款申请
    public function withdrawRequest(Request $request)
    {
        // 当前运营商取款设置
        $carrierWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::agentUser()->carrier_id)->first();
        // 判断是否允许代理取款
        if ($carrierWithdrawConf->is_allow_agent_withdraw != true) {
            return $this->sendErrorResponse('系统升级中,有疑问请联系客服', 404);
        }
        
        $input = $request->all();
        if (empty($input['player_bank_card'])) {
            return $this->sendErrorResponse('银行卡不能为空，请先添加银行卡', 404);
        }
        $input['carrier_id'] = \WinwinAuth::agentUser()->carrier_id;
        $input['agent_id'] = \WinwinAuth::agentUser()->id;
        
        // 生成取款单号
        $input['order_number'] = AgentWithdrawLog::generateOrderNumber();
        // 默认申请状态
        $input['status'] = AgentWithdrawLog::STATUS_WAITING_REVIEWED;
        
        $start_time = Carbon::now()->startOfDay();
        $end_time = Carbon::now()->endOfDay();
        
        // 判断当日取款成功次数是否已超出
        $agentWithdraw = AgentWithdrawLog::AccountOut()->whereBetween('reviewed_at', [
            $start_time,
            $end_time
        ])->count();
        if (($agentWithdraw != false) && ($agentWithdraw > $carrierWithdrawConf->agent_day_withdraw_success_limit_count)) {
            return $this->sendErrorResponse('当日取款成功次数不能超过' . $carrierWithdrawConf->agent_day_withdraw_success_limit_count, 404);
        }
        
        $agent = CarrierAgentUser::where('id', \WinwinAuth::agentUser()->id)->first();
        
        if (empty($agent->pay_password)) {
            if ($request->get('pay_password') != '000000') {
                return $this->sendErrorResponse('取款密码输入错误', 404);
            }
        } else {
            if (\Hash::check($request->get('pay_password'), $agent->pay_password) != true) {
                return $this->sendErrorResponse('取款密码输入错误', 404);
            }
        }
        
        try {
            \DB::transaction(function () use ($input, $agent) {
                $agentWithdrawLog = AgentWithdrawLog::create($input);
                if ($agentWithdrawLog) {
                    $agent->amount = $agent->amount - $input['apply_amount'];
                    $agent->save();
                }
            });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }
}
