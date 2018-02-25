<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Web\CreatePlayerDepositPayLogRequest;
use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Log\Base\BaseDepositModel;
use App\Models\Log\CarrierAgentDepositPayLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\PlayerBankCard;
use App\Repositories\Web\PlayerDepositPayLogRepository;
use App\Vendor\Pay\Gateway\PayGatewayServiceMap;
use App\Vendor\Pay\Gateway\PayOrderInterface;
use App\Vendor\Pay\Gateway\PayOrderRuntime;
use App\Vendor\Pay\Gateways\Common\Messages\RedirectMethod;
use App\Vendor\Pay\PayGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Vendor\Pay\OfflineDeposit\OfflineDepositOrderGateway;
use App\Models\Def\PayChannelType;
use App\Models\Player;
use App\Models\Def\BankType;
use App\Models\CarrierActivity;
use App\Models\CarrierPlayerLevel;
use Endroid\QrCode\QrCode;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午10:09
 */
class PlayerDepositPayLogController extends AppBaseController
{

    private $depositPayLogRepository;

    public function __construct(PlayerDepositPayLogRepository $depositPayLogRepository)
    {
        $this->depositPayLogRepository = $depositPayLogRepository;
    }

    /**
     * 会员存款界面
     *
     * @return \View
     */
    public function deposit(Request $request)
    {
        $act_id = $request->input('act_id');
        // 用户等级
        $playerLevelId = \WinwinAuth::memberUser()->player_level_id;
        
        $PlayerLevel = CarrierPlayerLevel::with(
            [
                'bankCardMap' => function ($query) {
                    $query->with(
                        [
                            'carrierBankCards' => function ($query) {
                                $query->available();
                            }
                        ]);
                }
            ])->find($playerLevelId);
        
        $onlinePayList = array();
        $otherPayList = array();
        // 根据玩家等级查找银行卡支付渠道
        foreach ($PlayerLevel->bankCardMap as $map) {
            if ($map->carrierBankCards && $map->carrierBankCards->use_purpose == 1 && in_array($map->carrierBankCards->show, [1,2])) {
                // 第三方在线支付(绑定第三方)
                $onlinePay = CarrierPayChannel::where('id', $map->carrierBankCards->id)->with(
                    'bindedThirdPartGateway.defPayChannel.payChannelType')->first();
                
                if ($onlinePay->bindedThirdPartGateway &&
                     $onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->isThirdPartPay()) {
                    if ($onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->isWeb())
                        $onlinePayList[$onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->id][] = $onlinePay;
                    // 其他支付(未绑定第三方)
                } else {
                    $otherPay = CarrierPayChannel::where('id', $map->carrierBankCards->id)->with(
                        'PayChannel.payChannelType')->where('use_purpose', 1)->whereIn('show',[1,2])->first();
                    $otherPayList[$otherPay->PayChannel->payChannelType->id][] = $otherPay;
                }
            }
        }
        // 活动列表
        $carrierActivityList = CarrierActivity::active()->where('is_deposit_display',
            CarrierActivity::DEPOSIT_DISPLAY_IS)->where('is_active_apply',1)->whereIn('bonuses_type', [1,2])->get();
        
        $array = [
            'onlinePayList' => $onlinePayList,
            'otherPayList' => $otherPayList,
            'carrierActivityList' => $carrierActivityList,
            'act_id' => $act_id
        ];
        
        return \WTemplate::depositPage()->with($array);
    }

    /**
     * 会员存款操作
     *
     * @param CreatePlayerDepositPayLogRequest $request
     */
    public function depositPayLogCreate(CreatePlayerDepositPayLogRequest $request)
    {
        if (\WinwinAuth::currentWebCarrier()->depositConf->is_allow_player_deposit == 0) {
            return $this->sendErrorResponse('禁止存款', 403);
        }
        $payChannelTypeId = $request->get('payChannelTypeId');
        if ($payChannelTypeId == PayChannelType::SCAN_CODE_PAY or
             $payChannelTypeId == PayChannelType::SCAN_CODE_COMPANY_PAY) {
            return $this->scanPay($request);
            // 线下银行转账
        } elseif ($payChannelTypeId == PayChannelType::BANK_TRANSFER_PAY) {
            return $this->offlineTransferDeposit($request);
            // 在线支付
        } elseif (in_array($payChannelTypeId,
            [
                PayChannelType::ONLINE_PAY,
                PayChannelType::ONLINE_OR_SCAN_PAY,
                PayChannelType::ONLINE_H5
            ])) {
            return $this->onlineTransferDeposit($request);
        }
    }

    public function onlineTransferDeposit(Request $request)
    {
        $data = [
            'amount' => $request->get('amount', 0),
            'bank_no' => $request->get('bank_no', ''),
            'pay_type' => $request->get('pay_type')
        ];
        $carrierPayChannelId = $request->get('carrierPayChannelId');
        $activityId = $request->get('activityId');
        $carrierPayChannel = CarrierPayChannel::find($carrierPayChannelId);
        
        if (! $carrierPayChannel || $carrierPayChannel->payChannel->payChannelType->isThirdPartPay() == false) {
            return $this->sendErrorResponse('运营商支付渠道有误', 403);
        }
        
        if ($data['amount'] <= 0 ||
             ($carrierPayChannel->single_deposit_minimum > 0 &&
             $data['amount'] < $carrierPayChannel->single_deposit_minimum) || ($carrierPayChannel->maximum_single_deposit >
             0 && $data['amount'] > $carrierPayChannel->maximum_single_deposit)) {
            return $this->sendErrorResponse('充值金额不正确');
        }
        
        try {
            \DB::beginTransaction();
            $payOrderRuntime = new PayOrderRuntime(\WinwinAuth::memberUser(), $carrierPayChannel, $data);
            $response = $payOrderRuntime->createOrder(null, null, $request->get('bankCode'));
            if ($activityId) {
                $activity = CarrierActivity::findOrFail($activityId);
                $response->payOrder->carrier_activity_id = $activity->id;
            }
            $response->payOrder->update();
            \DB::commit();
            // $redirectUrl = route('players.pay', [
            // 'pay_order_number' => $response->payOrder->pay_order_number
            // ]);
            
            // return $this->sendResponse($redirectUrl);
            
            return $this->pay($request, $response->payOrder->pay_order_number);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    public function pay(Request $request, $pay_order_number)
    {
        $prefix = substr($pay_order_number, 0, 3);
        if ($prefix == PlayerDepositPayLog::$prefix) {
            $player_deposit_pay_log = PlayerDepositPayLog::where('pay_order_number', $pay_order_number)->firstOrFail();
            $carrier_channel = $player_deposit_pay_log->carrierPayChannel;
            $channel_code = strtolower($carrier_channel->payChannel->channel_code);
            $ajaxPay = [
                'guofubao',
                'apipay'
            ];
            if (($player_deposit_pay_log->pay_type === 'bank' && strtolower($channel_code) == 'npay') ||
                 in_array(strtolower($channel_code), $ajaxPay)) {
                if ($request->ajax()) {
                    return $this->sendResponse(
                        [
                            'success' => 2007,
                            'redictUrl' => route('players.pay',
                                [
                                    'pay_order_number' => $pay_order_number
                                ])
                        ]);
                }
            }
            $response = PayGateway::gateway($channel_code)->create($player_deposit_pay_log);
            $method = $response->getRedirectMethod();
            if ($method == RedirectMethod::GET) {
                return redirect($response->getRedirectUrl());
            } else {
                // // dd($response->getRedirectResponse());
                // // exit();
                // return response($response->getRedirectResponse());
                $result = $response->getRedirectResponse();
                if (empty($result)) {
                    return $this->sendResponse(
                        [
                            'success' => 40001,
                            'message' => '请求出错！'
                        ]);
                }
                if (is_array($result)) {
                    if ($result['success'] == 200 && array_key_exists('qrcode', $result) && ! empty($result['qrcode'])) {
                        if (! $this->isMobile()) {
                            $result['qrcode'] = route('players.qrcode',
                                [
                                    'url' => urlencode($result['qrcode'])
                                ]);
                        }
                    } elseif ($result['success'] == 200 && array_key_exists('prepayUrl', $result) &&
                         ! empty($result['prepayUrl'])) {
                        $result['success'] = 2006;
                    }
                    return $this->sendResponse($result);
                } else {
                    return response($result);
                }
            }
            // dd($pay_gateway->create($player_deposit_pay_log));
        }
    }

    public function qrcode(Request $request)
    {
        $url = $request->get('url');
        if (empty($url)) {
            return $this->sendNotFoundResponse();
        }
        $qrcode = new QrCode(urldecode($url));
        return response($qrcode->writeString(), 200,
            [
                'Content-Type' => $qrcode->getContentType()
            ]);
    }

    public function offlineTransferDeposit(Request $request)
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $cardId = $request->get('cardId', '');
        $amount = $request->get('amount');
        $useName = $request->get('useName');
        $bankTypeId = $request->get('bankTypeId');
        $bankAccount = $request->get('bankAccount');
        $depositTime = $request->get('depositTime');
        $depositType = $request->get('depositType');
        $carrierPayChannelId = $request->get('carrierPayChannelId');
        $activityId = $request->get('activityId');
        $credential = $request->get('credential');
        
        $carrierPayChannel = CarrierPayChannel::find($carrierPayChannelId);
        $carrierActivity = CarrierActivity::find($activityId);
        $offlineDepositOrderGateway = new OfflineDepositOrderGateway();
        
        if (! $carrierPayChannel) {
            return $this->sendErrorResponse('运营商支付渠道有误', 403);
        }
        
        if ($amount <= 0 || ($carrierPayChannel->single_deposit_minimum > 0 &&
             $amount < $carrierPayChannel->single_deposit_minimum) || ($carrierPayChannel->maximum_single_deposit > 0 &&
             $amount > $carrierPayChannel->maximum_single_deposit)) {
            return $this->sendErrorResponse('充值金额不正确');
        }
        
        \DB::beginTransaction();
        try {
            $playerBankCard = PlayerBankCard::bankCard($bankAccount);
            if ($playerBankCard &&
                 ($bankTypeId != $playerBankCard->card_type || $useName != $playerBankCard->card_owner_name)) {
                return $this->sendErrorResponse('当前银行卡信息与已存在的银行卡信息不一致，请先检查。');
            }
            if (! $cardId && empty($playerBankCard)) { // 先添加银行再存款
                $player_bank_card = new PlayerBankCard();
                $player_bank_card->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                $player_bank_card->player_id = \WinwinAuth::memberUser()->player_id;
                $player_bank_card->card_account = $bankAccount;
                $player_bank_card->card_type = $bankTypeId;
                $player_bank_card->card_owner_name = $useName;
                $player_bank_card->card_birth_place = '无';
                $player_bank_card->created_at = Carbon::now();
                $player_bank_card->save();
                $playerBankCard = PlayerBankCard::where('card_account', $bankAccount)->first();
            }
            if (! $playerBankCard) {
                return $this->sendErrorResponse('存款银行卡有误');
            }
            $payOrderRuntime = new PayOrderRuntime(\WinwinAuth::memberUser(), $carrierPayChannel, $amount,
                $playerBankCard, $carrierActivity, $credential);
            
            $response = $payOrderRuntime->createOrder($depositTime, $depositType);
            if ($activityId) {
                $activity = CarrierActivity::findOrFail($activityId);
                $response->payOrder->carrier_activity_id = $activity->id;
            }
            $response->payOrder->update();
            \DB::commit();
            // return $this->sendResponse(route('players.financeStatistics') . '#deposit-record');
            return $this->sendResponse(route('players.depositRecords'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 扫码支付(个人、公司)
     *
     * @param int $pay_channel_id
     * @return \Illuminate\Http\JsonResponse|\Response
     */
    public function scanPay(Request $request)
    {
        $pay_channel_id = $request->get('payChannelId');
        $amount = $request->get('amount');
        // TODO $pay_channel_id = 19 微信扫码测试
        $payChannel = CarrierPayChannel::where('def_pay_channel_id', $pay_channel_id = 19)->first(); // $pay_channel_id
        try {
            $payGateWay = new PayOrderRuntime(\WinwinAuth::memberUser(), $payChannel, $amount);
            $orderResult = $payGateWay->createOrder();
            // dd($orderResult);
            $redirectUrl = route('players.createWeChatQRcode',
                [
                    'id' => $orderResult->payOrder->id /* , 'pay_url'=>urlencode($orderResult->payUrl) */
                ]);
            // dd($this->sendResponse($redirectUrl));
            return $this->sendResponse($redirectUrl);
        } catch (\Exception $e) {
            // throw $e;
            return $this->sendErrorResponse($e->getMessage(), 403);
        }
    }

    /**
     * 微信二维码界面
     *
     * @param Request $request
     */
    public function createWeChatQRcode($id)
    {
        $playerDepositPay = $this->depositPayLogRepository->find($id, [
            '*'
        ]);
        if ($playerDepositPay) {
            return \WTemplate::wechatScanPage()->with('playerDepositPay', $playerDepositPay);
        } else {
            return $this->sendErrorResponse('订单不存在', 403);
        }
    }

    /**
     * 不同存款类型界面
     *
     * @param Request $request
     * @return \View|void
     */
    public function DepositTypePage(Request $request)
    {
        $payChannelTypeId = $request->get('payChannelTypeId');
        $carrierPayChannelId = $request->get('carrierPayChannelId');
        $act_id = $request->get('act_id');

        // 获取银行卡号信息
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::where('player_id', $player_id)->with(
            [
                'bankCards' => function ($query) {
                    $query->active()
                        ->with('bankType');
                }
            ])
            ->get();
        
        // 银行列表
        $bankList = BankType::all();
        
        // 活动列表
        $carrierActivityList = CarrierActivity::active()->where('is_deposit_display',
            CarrierActivity::DEPOSIT_DISPLAY_IS)->whereIn('bonuses_type', [1,2])->where('is_active_apply',1)->orderBy('sort','desc')->get();
        
        $otherPay = CarrierPayChannel::where('id', $carrierPayChannelId)->with('PayChannel.payChannelType')->first();
        
        $other = [
            'otherPay' => $otherPay,
            'carrierActivityList' => $carrierActivityList
        ];
        
        $carrierPayChannel = CarrierPayChannel::with('payChannel')->where('id', $carrierPayChannelId)->first();
        $other['min_fee'] = $carrierPayChannel->single_deposit_minimum;
        $other['max_fee'] = $carrierPayChannel->maximum_single_deposit;
        if ($payChannelTypeId == PayChannelType::ONLINE_PAY) {
            // $onlinePay = CarrierPayChannel::where('id', $carrierPayChannelId)->with('bindedThirdPartGateway.defPayChannel.payChannelType')->first();
            $online = [
                'onlinePay' => $carrierPayChannel,
                'background' => $carrierPayChannel->payChannel->icon_path_url
            ];
            $bankListPre = $carrierPayChannel->payChannel->channel_code;
            $online['bankList'] = config('banklist.' . $bankListPre . '.online');
            $online['gateway'] = $carrierPayChannel->payChannel->channel_code === 'NPAY' ? 'bank' : '';
            
            $online = array_merge($other, $online);
            return \WTemplate::onlineDeposit()->with($online)->with(['act_id'=>$act_id]);
        } elseif ($payChannelTypeId == PayChannelType::SCAN_CODE_PAY) {
            
            return \WTemplate::scanCodeDeposit()->with(['act_id'=>$act_id]);
        } elseif ($payChannelTypeId == PayChannelType::BANK_TRANSFER_PAY) {
            $bankTransfer = [
                'player' => $player,
                'bankList' => $bankList,
                'transferType' => PlayerDepositPayLog::onlineTransferType(),
                'background' => $carrierPayChannel->payChannel->icon_path_url,
                'credential' => PlayerDepositPayLog::generateCredential()
            ];
            $bankTransfer = array_merge($other, $bankTransfer);
            return \WTemplate::bankTransferDeposit()->with($bankTransfer)->with(['act_id'=>$act_id]);
        } elseif ($payChannelTypeId == PayChannelType::SCAN_CODE_COMPANY_PAY) {
            $scanTransfer = [
                'onlinePay' => $carrierPayChannel,
                'background' => $carrierPayChannel->payChannel->icon_path_url
            ];
            // var_dump($other);exit;
            $scanTransfer['gateway'] = '';
            $other = array_merge($other, $scanTransfer);
            
            return \WTemplate::onlineScanDeposit()->with($other)->with(['act_id'=>$act_id]);
        } elseif ($payChannelTypeId == PayChannelType::POINT_CARD_PAY) {
            
            return \WTemplate::pointCardDeposit()->with($other)->with(['act_id'=>$act_id]);
        } elseif ($payChannelTypeId == PayChannelType::ONLINE_OR_SCAN_PAY) {
            
            $scanTransfer = [
                'onlinePay' => $carrierPayChannel,
                'background' => $carrierPayChannel->payChannel->icon_path_url
            ];
            $bankListPre = $carrierPayChannel->payChannel->channel_code;
            $scanTransfer['scan'] = config('banklist.' . $bankListPre . '.scan');
            $scanTransfer['gateway'] = array_first(array_flip($scanTransfer['scan']),
                function ($key, $val) {
                    return $val;
                });
            // var_dump($other);exit;
            $other = array_merge($other, $scanTransfer);
            return \WTemplate::onlineScanDeposit()->with($other)->with(['act_id'=>$act_id]);
        }
        
        return;
    }

    public function depositSecond(Request $request)
    {
        return \WTemplate::onlineDepositSecond();
    }

    /**
     * ajax搜索存款记录
     *
     * @param Request $request
     * @return \View
     */
    public function depositRecords(Request $request)
    {
        // 请求类型,默认是加载整个页面加数据, list(仅仅数据)
        $type = $request->get('type', '');
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
        $status = $request->get('status', '');
        $parameter = array(
            'status' => $status,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        
        $playerDepositPaylog = PlayerDepositPayLog::where('player_id', \WinwinAuth::memberUser()->player_id)->with(
            'carrierPayChannel.payChannel.payChannelType');
        
        if (isset($status) && $status != '') {
            $playerDepositPaylog = $playerDepositPaylog->where('status', $status);
        }
        if ($start_time) {
            $playerDepositPaylog = $playerDepositPaylog->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $playerDepositPaylog = $playerDepositPaylog->whereDate('created_at', '<=', $end_time);
        }
        $playerDepositPaylog = $playerDepositPaylog->orderBy('created_at', 'DESC')->paginate($perPage);
        $orderStatus = PlayerDepositPayLog::orderStatusMeta();
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::depositLists()->with('playerDepositPaylog', $playerDepositPaylog);
            }
            
            return \WTemplate::depositRecords()->with(
                [
                    'playerDepositPaylog' => $playerDepositPaylog,
                    'orderStatus' => $orderStatus
                ]);
        }
        
        if ($this->isMobile()) {
            $str = '';
            foreach ($playerDepositPaylog as $playerDeposit) {
                $str .= "{'存款编号':'" . $playerDeposit->pay_order_number . "','存款时间':'" . $playerDeposit->created_at .
                     "','存款类型':'" . $playerDeposit->carrierPayChannel->payChannel->payChannelType->type_name .
                     "','存款金额':'" . $playerDeposit->amount . "','手续费':'" . $playerDeposit->fee_amount . "','优惠金额':'" .
                     $playerDeposit->benefit_amount . "','实际到账':'" . $playerDeposit->finally_amount . "','处理时间':'" .
                     $playerDeposit->operate_time . "','状态':'" .
                     $playerDeposit::orderStatusMeta()[$playerDeposit->status] . "'},";
            }
            $str = rtrim($str, ',');
            
            return \WTemplate::depositRecords('m')->with(
                [
                    'str' => $str,
                    'playerDepositPaylog' => $playerDepositPaylog,
                    'orderStatus' => $orderStatus,
                    'parameter' => $parameter
                ]);
        } else {
            return \WTemplate::depositRecords()->with(
                [
                    'playerDepositPaylog' => $playerDepositPaylog,
                    'orderStatus' => $orderStatus,
                    'parameter' => $parameter
                ]);
        }
    }

    /**
     * 删除存款记录
     *
     * @param $pay_deposit_id
     * @return \Response
     * @throws \Exception
     */
    function depositRecordsDelete($pay_deposit_id)
    {
        // $player_id = \WinwinAuth::memberUser()->player_id;
        $PlayerDepositPayLog = PlayerDepositPayLog::where('id', '=', $pay_deposit_id)->delete();
        if ($PlayerDepositPayLog) {
            return $this->sendSuccessResponse(route('players.depositRecords '));
        } else {
            return $this->sendErrorResponse('删除失败', 403);
        }
    }

    /**
     * 删除存款记录
     *
     * @param $pay_deposit_id
     * @return \Response
     * @throws \Exception
     */
    function depositDropBatch(Request $request)
    {
        if (empty($request->get('depositLogIdArr'))) {
            return $this->sendErrorResponse('选择删除的记录', 403);
        }
        $PlayerDepositPayLog = PlayerDepositPayLog::whereIn('id', $request->get('depositLogIdArr'))->delete();
        if ($PlayerDepositPayLog) {
            return $this->sendSuccessResponse(route('players.depositRecords'));
        } else {
            return $this->sendErrorResponse('删除失败', 403);
        }
    }

    /**
     * 支付宝二维码界面
     *
     * @param Request $request
     */
    public function CreateAlipayQRcode(Request $request)
    {
        $pay_order_number = $request->get('pay_order_number');
        $pay_url = urldecode($request->get('pay_url'));
        $playerDepositPay = $this->depositPayLogRepository->findWhere(
            [
                'pay_order_number' => $pay_order_number
            ], [
                '*'
            ])->first();
        if ($playerDepositPay) {
            return view('Web.default.players_center.alipay_scan',
                [
                    'playerDepositPay' => $playerDepositPay,
                    'pay_url' => $pay_url
                ]);
        } else {
            return $this->sendErrorResponse('订单不存在', 403);
        }
    }
}