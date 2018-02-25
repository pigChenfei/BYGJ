<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Web\CreatePlayerBankCardRequest;
use App\Models\Conf\CarrierWithdrawConf;
use App\Models\Def\BankType;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Repositories\Carrier\PlayerRepository;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\PT\PTGameGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午10:09
 */
class PlayerWithdrawController extends AppBaseController
{

    public function withdrawRecords(Request $request)
    {
        $type = $request->get('type', '');
        $status = $request->get('status', '');
        $perPage = $request->get('perPage', 10);
        
        $t = $request->get('t', '');
        $start_time = '';
        $end_time = '';
        
        $time = time();
        if (! empty($t)) {
            if ($t == 1) {
                $start_time = date("Y-m-d", $time) . ' 00:00:00';
            } else if ($t == 2) {
                $start_time = date('Y-m-d H:i:s', strtotime('-1 sunday', time()));
            } else if ($t == 3) {
                $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m', time()) . '-01 00:00:00'));
            }
            $end_time = date("Y-m-d", $time) . ' 23:59:59';
        }
        
        $start_time = empty($start_time) ? $request->get('start_time', "") : $start_time;
        $end_time = empty($end_time) ? $request->get('end_time', "") : $end_time;
        
        $parameter = array(
            'status' => $status,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        
        $withdrawStatus = PlayerWithdrawLog::statusMeta();
        $playerWithdrawLog = PlayerWithdrawLog::where('player_id', \WinwinAuth::memberUser()->player_id);
        if ($status) {
            $playerWithdrawLog = $playerWithdrawLog->where('status', $status);
        }
        if ($start_time) {
            $playerWithdrawLog = $playerWithdrawLog->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $playerWithdrawLog = $playerWithdrawLog->whereDate('created_at', '<=', $end_time);
        }
        $playerWithdrawLog = $playerWithdrawLog->orderBy('created_at', 'DESC')->paginate($perPage);
        
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::withdrawLists()->with('playerWithdrawLog', $playerWithdrawLog);
            }
            return \WTemplate::withdrawRecords()->with(
                [
                    'playerWithdrawLog' => $playerWithdrawLog,
                    'withdrawStatus' => $withdrawStatus
                ]);
        }
        
        if ($this->isMobile()) {
            $str = '';
            foreach ($playerWithdrawLog as $item) {
                $str .= "{'取款编号':'" . $item->order_number . "','取款时间':'" . $item->withdraw_succeed_at . "','取款金额':'" .
                     $item->apply_amount . "','手续费':'" . $item->fee_amount . "','实际出款':'" .
                     $item->finally_withdraw_amount . "','备注':'" . $item->remark . "','状态':'" .
                     $item->statusMeta()[$item->status] . "'}";
            }
            
            $str = rtrim($str, ',');
            return \WTemplate::withdrawRecords('m')->with(
                [
                    'str' => $str,
                    'playerWithdrawLog' => $playerWithdrawLog,
                    'withdrawStatus' => $withdrawStatus,
                    'parameter' => $parameter
                ]);
        } else {
            return \WTemplate::withdrawRecords()->with(
                [
                    'playerWithdrawLog' => $playerWithdrawLog,
                    'withdrawStatus' => $withdrawStatus,
                    'parameter' => $parameter
                ]);
        }
    }

    /**
     * 新增银行卡
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBankCard(CreatePlayerBankCardRequest $request)
    {
        $input['card_owner_name'] = $request->get('card_owner_name');
        $input['card_account'] = $request->get('card_account');
        $input['card_type'] = $request->get('card_type');
        $input['card_birth_place'] = $request->get('card_birth_place');
        $input['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
        $input['player_id'] = \WinwinAuth::memberUser()->player_id;
        
        if ($input['card_owner_name'] != \WinwinAuth::memberUser()->real_name) {
            return $this->sendErrorResponse(
                [
                    'field' => 'card_owner_name',
                    'message' => '开户人姓名与账号真实姓名不一致'
                ], 404);
        }
        // 当前玩家所有的取款银行卡
        $playerBankCards = PlayerBankCard::where(
            [
                'carrier_id' => \WinwinAuth::memberUser()->carrier_id
            ])->count();
        if ($playerBankCards >= 7) {
            return $this->sendErrorResponse('添加失败，一人最多添加7张银行卡', 404);
        }
        $playerBankCards = new PlayerBankCard();
        $playerBankCard = $playerBankCards->create($input);
        if ($playerBankCard) {
            return $this->sendSuccessResponse();
        } else {
            return $this->sendErrorResponse('新增失败', 404);
        }
    }

    /**
     * 删除银行卡
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteBankCard(Request $request)
    {
        $card_id = $request->get('card_id');
        
        if (is_array($card_id)) {
            \DB::beginTransaction();
            foreach ($card_id as $id) {
                $del = PlayerBankCard::where('card_id', $id)->delete();
                if ($del == false) { // 其中一条删除失败
                    \DB::rollBack();
                    return $this->sendErrorResponse('删除失败，请重试');
                }
            }
            \DB::commit();
            return $this->sendResponse('删除成功');
        } else {
            $playerBankCards = new PlayerBankCard();
            $playerBankCard = $playerBankCards->where('card_id', $card_id)->delete();
            if ($playerBankCard) {
                return $this->sendSuccessResponse();
            } else {
                return $this->sendErrorResponse('删除失败', 404);
            }
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
        $carrierWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        
        $withdraw_number = $request->get('withdraw_number');
        if ($withdraw_number) {
            if ($withdraw_number < $carrierWithdrawConf->player_once_withdraw_min_sum) {
                return $this->sendErrorResponse('*单次取款最小限额为' . $carrierWithdrawConf->player_once_withdraw_min_sum . '元',
                    404);
            } elseif (($withdraw_number > \WinwinAuth::memberUser()->main_account_amount) &&
                 ($withdraw_number < $carrierWithdrawConf->player_once_withdraw_max_sum)) {
                return $this->sendErrorResponse('*账户余额不足', 404);
            } elseif ($withdraw_number > $carrierWithdrawConf->player_once_withdraw_max_sum) {
                return $this->sendErrorResponse('*单次取款最大限额为' . $carrierWithdrawConf->player_once_withdraw_max_sum . '元',
                    404);
            } else {
                return $this->sendResponse([],
                    '*注意：单次最低提款额为' . $carrierWithdrawConf->player_once_withdraw_min_sum . '元,最高' .
                         $carrierWithdrawConf->player_once_withdraw_max_sum . '元');
            }
        }
    }

    /**
     * 取款申请
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdrawApply(Request $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
        $input['player_id'] = \WinwinAuth::memberUser()->player_id;
        
        // 生成取款单号
        $input['order_number'] = PlayerWithdrawLog::generateOrderNumber();
        // 默认申请状态
        $input['status'] = PlayerWithdrawLog::STATUS_WAITING_REVIEWED;
        
        $start_time = Carbon::now()->startOfDay();
        $end_time = Carbon::now()->endOfDay();
        
        // 当前运营商取款设置
        $carrierWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        // 判断是否允许玩家取款
        if ($carrierWithdrawConf->is_allow_player_withdraw != true) {
            return $this->sendErrorResponse('系统升级中,有疑问请联系客服', 404);
        }
        
        // 判断玩家取款金额是否符合条件
        
        // 判断取款金额是否超过单日限额
        $playWithdrawSum = PlayerWithdrawLog::AccountOut()->whereBetween('created_at',
            [
                $start_time,
                $end_time
            ])->sum('apply_amount');
        
        if (($input['apply_amount'] > \WinwinAuth::memberUser()->main_account_amount)) {
            return $this->sendErrorResponse('账户余额不足', 404);
        } elseif ($input['apply_amount'] < $carrierWithdrawConf->player_once_withdraw_min_sum) {
            return $this->sendErrorResponse('单次取款最小限额为' . $carrierWithdrawConf->player_once_withdraw_min_sum . '元', 404);
        } elseif ($input['apply_amount'] > $carrierWithdrawConf->player_once_withdraw_max_sum) {
            return $this->sendErrorResponse('单次取款最大限额为' . $carrierWithdrawConf->player_once_withdraw_max_sum . '元', 404);
        } elseif (($input['apply_amount'] + $playWithdrawSum) > $carrierWithdrawConf->player_day_withdraw_max_sum) {
            return $this->sendErrorResponse('单日取款最大限额为' . $carrierWithdrawConf->player_day_withdraw_max_sum . '元', 404);
        }
        
        // 判断是否开启流水检查
        if ($carrierWithdrawConf->is_check_flow_water_when_withdraw) {
            $withDrawFlow = PlayerWithdrawFlowLimitLog::select(\DB::raw('SUM(limit_amount) as limit_amount'),
                \DB::raw('SUM(complete_limit_amount) as complete_limit_amount'))->unfinished()->first();
            $unfinished = bcsub($withDrawFlow['limit_amount'], $withDrawFlow['complete_limit_amount'], 2);
            $canGetAmt = bcsub(\WinwinAuth::memberUser()->main_account_amount, $unfinished, 2); // 当前可取款金额
            if (bccomp($input['apply_amount'], $canGetAmt) > 0) {
                return $this->sendErrorResponse('提款金额不可超过可提款金额', 403);
            }
        }
        
        // 判断当日取款成功次数是否已超出
        $playWithdraw = PlayerWithdrawLog::AccountOut()->whereBetween('created_at',
            [
                $start_time,
                $end_time
            ])->count();
        if (($playWithdraw != false) && ($playWithdraw >= $carrierWithdrawConf->player_day_withdraw_success_limit_count)) {
            return $this->sendErrorResponse(
                '当日取款成功次数不能超过' . $carrierWithdrawConf->player_day_withdraw_success_limit_count . '次', 404);
        }
        
        $player = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        
        if ($player->pay_password) {
            if (\Hash::check($request->get('pay_password'), $player->pay_password) != true) {
                return $this->sendErrorResponse('取款密码输入错误', 403);
            }
        } else {
            if ($request->get('pay_password') != '000000') {
                return $this->sendErrorResponse('取款密码输入错误，初始密码为000000', 403);
            }
        }
        
        try {
            \DB::transaction(
                function () use ($input, $player) {
                    $playerWithdrawLog = PlayerWithdrawLog::create($input);
                    if ($playerWithdrawLog) {
                        $player->frozen_main_account_amount = $input['apply_amount'];
                        $player->main_account_amount = round(
                            bcsub($player->main_account_amount, $input['apply_amount'], 3), 2);
                        $player->save();
                    }
                });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            \Log::info('用户取款错误' . $e->getMessage());
            return $this->sendErrorResponse('取款异常');
        }
    }

    /**
     */
    public function withdrawApplyone(Request $request)
    {
        $player_bank_card = $request->get('player_bank_card');
        $apply_amount = $request->get('apply_amount');
        return view('Web.mobile.player_centers.finance_center.withdrawApplyone',
            compact('player_bank_card', 'apply_amount'));
    }

    /**
     * 手机端银行卡管理
     *
     * @return \View
     */
    public function bankcard()
    {
        // 银行卡class
        $logoArr = config('constants.logoArr');
        // 当前玩家所有的取款银行卡
        $playerBankCards = PlayerBankCard::with('bankType')->get();
        // 处理银行卡号 *
        foreach ($playerBankCards as $playerBankCard) {
            if ($playerBankCard->card_account) {
                $playerBankCard->card_account = PlayerBankCard::replaceStar($playerBankCard->card_account, 4, 11);
            }
        }
        return view('Web.mobile.player_centers.finance_center.bankcard', compact('playerBankCards', 'logoArr'));
    }

    /**
     * 手机端添加银行卡页面
     *
     * @return \View
     */
    public function addBankcardPage()
    {
        $banks = BankType::pluck('bank_name', 'type_id');
        // dump(json_encode($banks));
        return view('Web.mobile.player_centers.finance_center.addBankcard', compact('banks'));
    }
}