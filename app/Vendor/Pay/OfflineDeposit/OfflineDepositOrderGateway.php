<?php
namespace App\Vendor\Pay\OfflineDeposit;

use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Gateway\PayOrderAbstract;
use App\Vendor\Pay\Gateway\PayOrderFetchResponse;
use App\Vendor\Pay\Gateway\PayOrderInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CarrierActivity;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/21
 * Time: 下午3:31
 */
class OfflineDepositOrderGateway extends PayOrderAbstract
{

    /**
     * 创建订单
     *
     * @param Player $player
     * @param CarrierPayChannel $payChannel
     * @param float $amount
     * @return PayOrderFetchResponse
     */
    public function createOrder(Player $player, CarrierPayChannel $payChannel, $amount,
        PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null,
        $credential = null)
    {
        parent::createOrder($player, $payChannel, $amount, $playerBankCard, $depositTime, $depositType,
            $carrierActivity);
        
        $depositTime = $depositTime . ":00";
        if (! $depositTime) {
            throw new PayOrderRuntimeException('缺少转账时间');
        }
        try {
            Carbon::createFromFormat('Y-m-d H:i:s', $depositTime);
        } catch (\Exception $e) {
            throw new PayOrderRuntimeException('缺少转账时间');
        }
        
        if (! $playerBankCard) {
            throw new PayOrderRuntimeException('缺少会员转账银行卡');
        }
        
        if (! $depositType || ! array_key_exists($depositType, PlayerDepositPayLog::onlineTransferType())) {
            throw new PayOrderRuntimeException('转账类型不合法');
        }
        
        $order = new PlayerDepositPayLog();
        $order->ip = RealIpHelper::getIp();
        $order->carrier_id = $player->carrier_id;
        $order->player_id = $player->player_id;
        $order->pay_order_number = PlayerDepositPayLog::generatePayNumber();
        $order->carrier_pay_channel = $payChannel->id;
        $order->amount = $amount;
        $order->credential = $credential ?? PlayerDepositPayLog::generateCredential();
        $order->player_bank_card = $playerBankCard->card_id;
        $order->status = PlayerDepositPayLog::ORDER_STATUS_WAITING_REVIEW;
        $order->offline_transfer_deposit_at = $depositTime;
        $order->offline_transfer_deposit_type = $depositType;
        $order->carrier_activity_id = $carrierActivity ? $carrierActivity->id : null;
        $order->save();
        $response = new PayOrderFetchResponse();
        $response->payOrder = $order;
        $response->payType = PayOrderFetchResponse::WEB_PAY_TYPE_OFF_LINE_TRANSFER;
        return $response;
    }

    /**
     * 检测订单是否合法
     *
     * @param Request $request
     * @return \Response
     */
    public function verifyOrderIsLegal(Request $request)
    {}

    public function getBankList()
    {
        // TODO: 从数据库查线下转账的银行卡列表
    }
}