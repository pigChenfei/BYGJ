<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameGateway\PT;


use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\Query\QueryResult;
use Curl\Curl;

class PTGameGatewayQueryBuilder
{

    //const API_HOST = 'https://kioskpublicapi.grandmandarin88.com/';
    const API_HOST = 'https://kioskpublicapi.luckyspin88.com/'; //最新接口请求地址20171207
    public $EntityKey;
    public $KioskName;
    public $AdminName;
    public $PREFIX;

    /**
     * @var array
     */
    private $header = [
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
        "Keep-Alive:timeout=5, max=100",
        "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3",
        "Accept-Language:es-ES,es;q=0.8",
        "Pragma: "
    ];


    /**
     *新增会员API
     */
    const API_PLAYER_CREATE = 'player/create';

    /**
     *会员账目
     */
    const API_PLAYER_BALANCE = 'player/balance';


    /**
     *获取游戏流水记录, 由于最多查询十分钟以内的, 所以暂时不适用
     */
    const API_GAME_FLOW = 'game/flow';

    const API_PLAYER_WITHDRAW = 'player/withdraw';

    const API_PLAYER_DEPOSIT = 'player/deposit';

    const API_PLAYER_GIVE_BONUS = 'player/givebonus';

    const API_PLAYER_ONLINE = 'player/online';

    const API_PLAYER_LOGOUT = 'player/logout';

    const API_PLAYER_RESET_FAILED_LOGIN_ATTEMPTS = 'player/resetFailedLogin';

    const API_PLAYER_LIST = 'player/list';

    const API_PLAYER_UPDATE = 'player/update';


    /**
     * 会员投注流水汇总报告汇总
     *"result" => array:1 [▼
    0 => array:18 [▼
    "STATSDATE" => "2017-03-22"
    "CURRENCYCODE" => "CNY"
    "ACTIVEPLAYERS" => "1"
    "BALANCECHANGE" => "50.37"
    "DEPOSITS" => "50.4"
    "WITHDRAWS" => ".4"
    "COMPENSATION" => "0"
    "COMMISSION" => "0"
    "BONUSES" => "0"
    "COMPS" => "0"
    "PROGRESSIVEBETS" => "0"
    "PROGRESSIVEWINS" => "0"
    "BETS" => "1.12"
    "WINS" => "1.49"
    "NETLOSS" => "-.37"
    "NETPURCHASE" => "50"
    "NETGAMING" => "-.37"
    "HOUSEEARNINGS" => "-.37"
    ]
    ]
    "pagination" => array:4 [▼
    "currentPage" => 1
    "totalPages" => 1
    "itemsPerPage" => 1
    "totalCount" => 1
    ]
     */
    const API_PLAYER_STATES_DATA = 'customreport/getdata/reportname/PlayerStats';


    /**
     *会员游戏投注流水记录,如果带有playername参数则可以查几天的数据, 否则只能查询最近半小时的数据
     */
    const API_PLAYER_GAMES_BETTING_FLOW = 'customreport/getdata/reportname/PlayerGames';

    /**
     * @var array
     */
    private $parameter  = [];

    private $apiFunction = null;

    /**
     * @var resource
     */
    private $curl;

    /**
     * PTGameGatewayQueryBuilder constructor.
     */
    public function __construct()
    {

        $this->EntityKey = GameGatewayRunTime::$env == GameGatewayRunTime::PRODUCTION ?
            'd97ddafeeb4f82adc72ae4e558cc292bd23ad85c229600c5c6481876b472a964a47dd93879df24b350c38bc3762d86f1f1a6244e1dc11c2b24003e80decbb3f4':
            '47cd325b04cb2249651edaa7bf736da4400b245427c834db746b420be7334975cfbea4e7ef61fd7be201f2b89192b5a52e24e885ef6b51a29c6fbb839deb1ef4';
        $this->KioskName = GameGatewayRunTime::$env == GameGatewayRunTime::PRODUCTION ?
            'TTC':
            'T';
        $this->AdminName = GameGatewayRunTime::$env == GameGatewayRunTime::PRODUCTION ?
            'TTCADMIN':
            'WIN88TESTADMIN';
        $this->PREFIX    = GameGatewayRunTime::$env == GameGatewayRunTime::PRODUCTION ?
            'TTC':
            'TEST_';
        $this->header[] = "X_ENTITY_KEY: " . $this->EntityKey;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_PORT , 443);
        curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSLCERT, app_path('Vendor/GameGateway/PT/Cert/WIN88.pem'));
       // curl_setopt($this->curl, CURLOPT_SSLCERTPASSWD,'changeit');
       // curl_setopt($this->curl, CURLOPT_SSLCERTPASSWD,'O8w1oUxm');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSLKEY, app_path('Vendor/GameGateway/PT/Cert/WIN88.key'));
    }


    /**
     * @return string
     */
    private function getQueryUrl(){
        if(!$this->apiFunction){
            throw new \InvalidArgumentException('Api function cannot be null');
        }
        $queryUrl = self::API_HOST;
        $queryUrl .= $this->apiFunction;
        foreach ($this->parameter as $key => $value){
            $queryUrl .= '/'.$key.'/'.$value;
        }
        return $queryUrl;
    }


    /**
     * 添加设置项
     * @param $key
     * @param $value
     */
    public function setOpt($key, $value){
        $this->curl->setOpt($key,$value);
    }

    public function setApiFunction($functionName){
        $this->apiFunction = $functionName;
    }

    /**
     * 新增参数
     * @param $key
     * @param $value
     */
    public function addParameter($key, $value){
        $this->parameter[$key] = $value;
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


    /**
     * @param array $data
     * @return QueryResult
     */
    public function fetch(){
        \WLog::info('===>创建PT数据请求Url',['url' => $this->getQueryUrl()]);
        curl_setopt($this->curl,CURLOPT_URL,$this->getQueryUrl());
        $execResponse = curl_exec($this->curl);
        $response = new QueryResult();
        if(curl_error($this->curl)){
            \WLog::error('===>PT请求结果失败',['message' => curl_error($this->curl)]);
            curl_close($this->curl);
            throw new GameGateWayRuntimeException(curl_error($this->curl));
        }else{
            $data= json_decode($execResponse,true);
            \WLog::info('===>PT请求结果成功',$data);
            if(isset($data['error'])){
                if(is_string($data['error'])){
                    curl_close($this->curl);
                    throw new GameGateWayRuntimeException($data['error']);
                }
                curl_close($this->curl);
                throw new GameGateWayRuntimeException(json_encode($data['error']));
            }
            $response->data = $data;
        }
        curl_close($this->curl);
        return $response;
    }

}