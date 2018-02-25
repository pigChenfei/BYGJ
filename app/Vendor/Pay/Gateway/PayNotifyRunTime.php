<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/21
 * Time: 下午4:08
 */

namespace App\Vendor\Pay\Gateway;


use App\Models\Conf\CarrierThirdPartPay;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PayNotifyRunTime
{


    /**
     * @var PayOrderInterface
     */
    private $payGateway;


    /**
     * @var Request
     */
    private $request;


    /**
     * @var CarrierThirdPartPay
     */
    private $thirdPartPayConfigure;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $host = $request->getHost();
        //根据Host查找配置信息
        $thirdPartPayConfigure = CarrierThirdPartPay::retrieveByHost($host)->first();
        if(empty($thirdPartPayConfigure)){
            throw new PayOrderRuntimeException('Unknown Response host');
        }
        $this->thirdPartPayConfigure = $thirdPartPayConfigure;
        //如果是三方支付
        if($thirdPartPayConfigure->defPayChannel->payChannelType->isThirdPartPay() == true){
            $this->payGateway = new PayGatewayServiceMap::$payServiceMap[$thirdPartPayConfigure->defPayChannel->channel_code];
        }
        //Todo 如果不是三方支付
    }


    /**
     * @return \Response
     * @throws \Exception
     */
    public function handleNotifyRequest(){
        try{
            $response = $this->payGateway->verifyOrderIsLegal($this->request);
            $playerDepositOrder = $this->payGateway->getDepositPayLogWhenVerifySuccess();
            //检测运营商是否允许三方自动到账
            if($playerDepositOrder->isPayedSuccessfully() == false){
                \DB::transaction(function () use ($playerDepositOrder){
                    if($playerDepositOrder->carrier->depositConf->is_allow_third_part_deposit_auto_arrival){
                        //如果是自动到账, 那么设置操作时间为当前时间.
                        $playerDepositOrder->status = PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED;
                        $playerDepositOrder->operate_time = time();
                        //新增取款流水限制
                        $withdrawFlowLimit = new PlayerWithdrawFlowLimitLog();
                        $withdrawFlowLimit->carrier_id = $playerDepositOrder->carrier_id;
                        $withdrawFlowLimit->player_id  = $playerDepositOrder->player_id;
                        $withdrawFlowLimit->limit_amount = $playerDepositOrder->amount;
                        $withdrawFlowLimit->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_PLAYER_DEPOSIT;
                        $withdrawFlowLimit->limit_amount > 0 && $withdrawFlowLimit->save();
                    }else{
                        $playerDepositOrder->status = PlayerDepositPayLog::ORDER_STATUS_WAITING_REVIEW;
                    }
                    // 保存订单状态
                    $playerDepositOrder->update();
                });
            }
            return $response;
        }catch (\Exception $e){
            throw $e;
        }
    }

}