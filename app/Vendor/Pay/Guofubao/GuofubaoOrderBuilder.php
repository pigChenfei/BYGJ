<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/20
 * Time: 下午5:26
 */

namespace App\Vendor\Pay\Guofubao;


use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Gateway\PayOrderFetchResponse;
use App\Vendor\Pay\Gateway\PayOrderRuntime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

class GuofubaoOrderBuilder
{

    const ORDER_CREATE_URL = 'https://gateway.gopay.com.cn/Trans/WebClientAction.do';
    /**
     * @var CarrierPayChannel
     */
    private $carrierPayChannel;

    /**
     * @var PlayerDepositPayLog
     */
    public $payOrder;

    private $primaryKey;

    /**
     * 金额
     * @var
     */
    private $amount;

    private $params = [];

    /**
     * @param 
     * @param PlayerDepositPayLog $payLog
     */
    public function __construct(CarrierPayChannel $payChannel, PlayerDepositPayLog &$payLog, $amount)
    {
        if (!$payChannel->bindedThirdPartGateway) {
            throw new PayOrderRuntimeException('无法找到第三方支付网关配置!');
        }
        if (!$payChannel->bindedThirdPartGateway->merchant_identify_code) {
            throw new PayOrderRuntimeException('没有配置三方支付网关商户识别码,请检查!');
        }
        if (!$payLog->carrier_id) {
            throw new PayOrderRuntimeException('该订单没有所属运营商,请检查!');
        }
        $this->carrierPayChannel = $payChannel;
        $this->payOrder = $payLog;
        $this->amount = $amount;
        $this->primaryKey = $this->carrierPayChannel->bindedThirdPartGateway->merchant_identify_code;
    }

    public function buildParam()
    {
        $this->payOrder->pay_order_number = PlayerDepositPayLog::generatePayNumber();
        $this->payOrder->amount = $this->amount;
        $this->payOrder->carrier_pay_channel = $this->carrierPayChannel->id;
        $this->payOrder->save();
        $this->params = array_merge($this->params, [
            'version' => 2.2,//网关版本号
            'charset' => 2,
            'language' => 1,
            'signType'=> 1,
            'tranCode' => "8888",//交易代码
            'merchantID' => $this->carrierPayChannel->bindedThirdPartGateway->merchant_number,//商户代码
            'merOrderNum' => $this->payOrder->pay_order_number,//订单号
            'tranAmt' => number_format($this->amount,2,".",""),//交易金额
            'feeAmt' => '',
            'currencyType' => 156,//币种
            //通知地址
            'frontMerUrl' => '',
            'backgroundMerUrl' => 'http://' . $this->carrierPayChannel->bindedThirdPartGateway->merchant_bind_domain . '/' . PayOrderRuntime::orderNotifyRouteName(),
            'tranDateTime' => $this->payOrder->created_at->format('YmdHis'),//交易时间
            'virCardNoIn' => $this->carrierPayChannel->bindedThirdPartGateway->vir_card_no_in,//国付宝转入账户
            'tranIP' => RealIpHelper::getIp(),
            'isRepeatSubmit' => '0',//订单是否允许重复提交
            'goodsName' => 'Guofubao Pay Order',//商品名称
            'goodsDetail' => 'Guofubao Pay Order',//商品详情
            'buyerName' => '',
            'buyerContact' => '',
            'merRemark1' => '',
            'merRemark2' => '',
//            'buyerRealMobile' => '',
//            'buyerRealName' => '',
//            'buyerRealCertNo' => '',
//            'buyerRealBankAcct' => '',
            'gopayServerTime' => $this->payOrder->created_at->format('YmdHis'),
            'userType' => '1',
            ]);
    }

    /**
     * 新增参数
     * @param $key
     * @param $value
     */
    public function addParameter($key, $value){
        $this->params[$key] = $value;
    }


    /**
     * 批量新增参数
     * @param array $array
     */
    public function addParameters($array){
        foreach ($array as $key => $value){
            $this->addParameter($key,$value);
        }
    }

    private function signParam()
    {
        $signStr='version=['.$this->params['version'].']tranCode=['.$this->params['tranCode'].']merchantID=['.$this->params['merchantID'].']merOrderNum=['.$this->params['merOrderNum'].']tranAmt=['.$this->params['tranAmt'].']feeAmt=[]tranDateTime=['.$this->params['tranDateTime'].']frontMerUrl=[]backgroundMerUrl=['.$this->params['backgroundMerUrl'].']orderId=[]gopayOutOrderId=[]tranIP=['.$this->params['tranIP'].']respCode=[]gopayServerTime=['.$this->params['gopayServerTime'].']VerficationCode=['.$this->primaryKey.']';
        $signValue = md5($signStr);
        $this->params['signValue'] = $signValue;
    }

    
    /**
     * @return PayOrderFetchResponse
     */
    public function fetchOrderResult()
    {
        $this->buildParam();
        \WLog::info('==========智付请求数据==========',['data' => $this->params]);
        //var_dump($this->params) ;
        $this->signParam();
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,self::ORDER_CREATE_URL);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $this->params);
        $return = curl_exec($ch);
        curl_close($ch);
        //var_dump($return);
        
        
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, self::ORDER_CREATE_URL);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 10);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        var_dump($response);
    }
    
    
//    public function fetchOrderResult()
//    {
//        $this->buildParam();
//        \WLog::info('==========国付宝请求数据==========',['data' => $this->params]);
//        $this->signParam();
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, self::ORDER_CREATE_URL);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 10);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        if($response == false)
//        {
////            dd(11);
//        }else{
//            var_dump($response->respCode);
//        }
        
        
//        if ($response) {
//             var_dump($response);
////            dd($response);
////            $result = json_decode(json_encode(simplexml_load_string($response)), true);
////            if(!isset($result['response'])){
////                throw new PayOrderRuntimeException('国付宝网关出错,错误数据:'.json_encode($result));
////            }
////            if(isset($result['response']['resp_code']) && $result['response']['resp_code'] == 'FAIL'){
////                throw new PayOrderRuntimeException('国付宝网关出错,错误数据:'.$result['response']['result_desc']);
////            }
////            if(!isset($result['response']['qrcode'])){
////                throw new PayOrderRuntimeException('国付宝网关数据解析错误,信息:'.$result['response']['result_desc']);
////            }
//            $response = new PayOrderFetchResponse();
//            $response->payOrder = $this->payOrder;
//            $response->payUrl = "https://gateway.gopay.com.cn/Trans/WebClientAction.do";
//            $response->payType = PayOrderFetchResponse::WEB_PAY_TYPE_REDIRECT;
//            return $response;
//        } else {
//            
//            throw new PayOrderRuntimeException('国付宝网关出错,错误数据:' . $response);
//        }
//    }

    public function verifyNotifyDataIsLegal(Request $request)
    {        
        $this->params = [];
        $this->params = array_merge($this->params,[
            'version' => $request->get('version'),//网关版本号
            'merchantID' => $request->get('merchantID'),//商户代码
            'order_amount' => $request->get('order_amount'),//订单金额
            'merOrderNum' => $request->get('merOrderNum'),//商家订单编号
            'tranDateTime' => $request->get('tranDateTime'),//商家订单时间
            'respCode' => $request->get('respCode'),
        ]);
        $signStr='version=['.$this->params['version'].']merchantID=['.$this->params['merchantID'].']merOrderNum=['.$this->params['merOrderNum'].']';
        $flag = md5($signStr);
        if($flag == $request->get('signValue')){
            if($request->get('respCode')=='0000')
            {
                \WLog::info('==========支付回调验证成功==========');
                return true;
            }
        }
        throw new PayOrderRuntimeException('国付宝订单验证失败,非法订单数据.');
    }

}