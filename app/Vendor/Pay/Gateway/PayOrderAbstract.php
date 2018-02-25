<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 下午 8:17
 */
namespace App\Vendor\Pay\Gateway;

use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use Carbon\Carbon;

abstract class PayOrderAbstract implements PayOrderInterface
{

    /**
     *
     * @var PayOrderFetchResponse
     */
    protected $orderFetchResponse;

    /**
     *
     * @var PlayerDepositPayLog
     */
    public $playerDepositPayOrder;

    public function applyCustomPayCondition(\Closure $callable = null)
    {
        if ($callable) {
            $callable($this);
        }
    }

    public function getDepositPayLogWhenVerifySuccess()
    {
        return $this->playerDepositPayOrder;
    }

    public function createOrder(Player $player, CarrierPayChannel $payChannel, $amount,
        PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null,
        $credential = null)
    {
        // 获取当日该用户的存款次数
        $count = PlayerDepositPayLog::byPlayerId($player->player_id)->where('created_at', '>=',
            Carbon::now()->startOfDay()
                ->toDateTimeString())
            ->where('created_at', '<=', Carbon::now()->endOfDay()
            ->toDateTimeString())
            ->count('*');
        
        if ($payChannel->single_day_deposit_limit > 0 && $count >= $payChannel->single_day_deposit_limit) {
            throw new PayOrderRuntimeException('超过当日存款次数');
        }
        // 最大存款额
        if ($payChannel->maximum_single_deposit > 0 && $amount > $payChannel->maximum_single_deposit) {
            throw new PayOrderRuntimeException('超过最大存款额限制');
        }
        // 最小存款额
        if ($payChannel->single_deposit_minimum > 0 && $amount < $payChannel->single_deposit_minimum) {
            throw new PayOrderRuntimeException('低于最小存款额限制');
        }
    }
}