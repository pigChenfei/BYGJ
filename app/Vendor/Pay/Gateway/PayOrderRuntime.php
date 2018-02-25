<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/20
 * Time: 下午4:23
 */
namespace App\Vendor\Pay\Gateway;

use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Guofubao\GuofubaoPayOrderGateway;
use App\Vendor\Pay\Zhifu\ZhifuPayOrderGateway;

class PayOrderRuntime
{

    /**
     *
     * @var Player
     */
    private $player;

    /**
     *
     * @var CarrierPayChannel
     */
    private $carrierPayChannel;

    /**
     *
     * @var PayOrderInterface
     */
    private $payOrderGateway;

    /**
     *
     * @var
     *
     */
    private $param;

    /**
     *
     * @var PlayerBankCard
     */
    private $playerBankCard;

    private $credential;

    /**
     * PayOrderRuntime constructor.
     */
    public function __construct(Player $player, CarrierPayChannel $payChannel, $param,
        PlayerBankCard $playerBankCard = null, CarrierActivity $carrierActivity = null, $credential = null)
    {
        $player->checkLocked();
        $this->player = $player;
        $this->carrierPayChannel = $payChannel;
        $this->param = $param;
        $this->playerBankCard = $playerBankCard;
        $this->carrierActivity = $carrierActivity;
        $this->credential = $credential;
    }

    /**
     *
     * @return PayOrderInterface
     */
    private function getPayOrderGateway()
    {
        if (! $this->payOrderGateway) {
            $payChannel = $this->carrierPayChannel->payChannel;
            // 如果是银行转账
            if ($payChannel->payChannelType->isCompanyBankTransfer()) {
                $this->payOrderGateway = new PayGatewayServiceMap::$payServiceMap[PayGatewayServiceMap::GATEWAY_OFFLINE_DEPOSIT]();
            } else {
                try {
                    $this->payOrderGateway = new PayGatewayServiceMap::$payServiceMap[$this->carrierPayChannel->payChannel->channel_code]();
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
        return $this->payOrderGateway;
    }

    public static function orderRouteList()
    {
        \Route::post('paynotify', 'PayNotifyController@index')->name('paynotify');
    }

    public static function orderNotifyRouteName()
    {
        return 'paynotify';
    }

    /**
     * 创建订单
     *
     * @param null $depositTime 存款时间,线下转账专用参数
     * @param null $depositType 存款类型,线下转账专用参数 PlayerDepositPayLog::OFFLINE_TRANSFER_ATM 或者 PlayerDepositPayLog::OFFLINE_TRANSFER_BANK
     * @return PayOrderFetchResponse
     */
    public function createOrder($depositTime = null, $depositType = null, $depositBankParam = null)
    {
        // 判断账号是否正常
        $this->player->checkLocked();
        // TODO 判断当前会员等级是否可以存款, 判断当前运营商系统设置是否可以存款,判断存款金额是否在系统设置区间以内,判断是否达到当日存款次数上线
        $gateWay = $this->getPayOrderGateway();
        
        if ($depositBankParam) {
            if ($gateWay instanceof GuofubaoPayOrderGateway) {
                $gateWay->applyCustomPayCondition(
                    function (GuofubaoPayOrderGateway &$gateway) use ($depositBankParam) {
                        $gateway->orderBankCode = $depositBankParam;
                    });
            }
        }
        return $gateWay->createOrder($this->player, $this->carrierPayChannel, $this->param, $this->playerBankCard,
            $depositTime, $depositType, $this->carrierActivity, $this->credential);
    }

    /**
     *
     * @return PayBank[]
     */
    public function bankList()
    {
        return $this->getPayOrderGateway()->getBankList();
    }
}