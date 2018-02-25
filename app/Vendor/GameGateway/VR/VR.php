<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\GameGateway\VR;

use App\Models\PlayerGameAccount;
use App\Models\Def\MainGamePlat;
use App\Models\Def\Game;
use App\Models\Log\PlayerAccountLog;
use App\Http\Controllers\Web\GameController;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use App\Models\PlayerTransfer;
use Models\Log\GameNoneExists;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use Curl\Curl;

class VR
{

    // 查询订单
    const KEY = '0B0R4P62F0TBL62PZZJFRPV00PZL440X';

    // 服务器
    const URL = 'http://fe.vrbetdemo.com';

    // id
    const MERCHANTID = 'TTC';

    // 版本信息
    const VERSION = '1.0';

    // 创建用户
    const CREATEUSER = '/Account/CreateUser';

    // 用户登录
    const LOGIN = '/Account/LoginValidate';

    // 转帐
    const TRANSACTION = '/UserWallet/Transaction';

    // 踢线
    const KICKUSER = '/Account/KickUser';

    // 查询转帐记录
    const TRANSACTIONRECORD = '/UserWallet/TransactionRecord';

    // 余额查询
    const BALANCE = '/UserWallet/Balance';

    // 抓单
    const BETRECORD = '/MerchantQuery/Bet';

    private $lastSynchronizeTime;

    public static function registerRoutes()
    {
        return [ // 'ptLoginRedirect' => 'PTGameRedirectController@index'
];
    }

    public function vrCurl($url, $body = '', $method = 'post')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            return false;
        } else {
            $output = json_decode($this->apiDecode($output), true);
            return $output;
        }
    }

    /**
     * 生成随机字符串
     */
    private function generate_value($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY0123456789';
        $value = '';
        for ($i = 0; $i < $length; $i ++) {
            $value .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $value;
    }

    /**
     * 更新同步时间到磁盘
     *
     * @param
     *            $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time)
    {
        \Storage::put('VR_synchronizedTimestamp', $time);
    }

    public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null, $page)
    {
        // $start_time = str_replace(' ','T',$condition->start_date).'Z' ;
        
        // $end_time = str_replace(' ','T',$condition->end_date).'Z' ;
        $start_time = date('Y-m-d\TH:i:s\Z', strtotime($condition->start_date) - 8 * 3600);
        $end_time = date('Y-m-d\TH:i:s\Z', strtotime($condition->end_date) - 8 * 3600);
        
        /*
         * $start_time = str_replace(' ','T',date("Y-m-d H:i:s",time()-3600*10000)) ; //当前时间1000小时前的时间
         * $end_time = str_replace(' ','T',date("Y-m-d H:i:s",time())) ; //当前时间
         */
        try {
            $response = $this->getGamesRecord($start_time, $end_time, $page);
            \WLog::info('====>进入fetchGameFlowResult同步数据' . json_encode($response));
            $result = $response;
            if ($result['betRecords']) {
                $dataArr = array();
                foreach ($result['betRecords'] as $k => $value) {
                    // 过滤没有投注金额的投注记录
                    if ($value['cost']) { // "sum_of_wage:下注额 ; sum_of_payout:派彩，不扣除本金
                        $dataArr[] = $value;
                    }
                }
                $flowRecord = array();
                $i = 0;
                // var_dump($flowRecord) ; echo "print_data:<br>" ;
                foreach ($dataArr as $element) {
                    \WLog::info('====>进入循环组装数据');
                    $flowRecord[$i]['playerName'] = $element['playerName'];
                    $flowRecord[$i]['gameCode'] = $element['channelCode']; // 游戏代码
                    $flowRecord[$i]['bet'] = $element['cost'];
                    $flowRecord[$i]['win'] = $element['playerPrize'];
                    if ($element['state'] != 0) {
                        $flowRecord[$i]['status'] = 1;
                    } else {
                        $flowRecord[$i]['status'] = 0;
                    }
                    
                    $flowRecord[$i]['date'] = $element['createTime'];
                    $flowRecord[$i]['plat_game_code'] = $element['channelCode'];
                    $flowRecord[$i]['code'] = $element['serialNumber']; // 游戏流水号
                    $flowRecord[$i]['game_id'] = $element['channelId'];
                    if ($element['state'] == 1) { // sum_of_refund 退费，可能会依网络问题造成投注失败
                        $flowRecord[$i]['isBetAvailable'] = false; // 无效投注
                        $flowRecord[$i]['availableBet'] = 0;
                    } else {
                        $flowRecord[$i]['isBetAvailable'] = true; // 有效投注
                        $flowRecord[$i]['availableBet'] = $element['cost']; // 有效投注额
                    }
                    $i ++;
                }
                \WLog::info('====>进入fetchGameFlowResult组装数据' . json_encode($flowRecord));
                return $flowRecord;
            }
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition, PlayerGameAccount $gameAccount = null)
    {
        $page = - 1;
        do { // 循环查询数据
            $page ++; // 查询的第几页的数据
            try {
                $betFlowRecords = $this->fetchGameFlowResult($condition, $gameAccount, $page);
                if (empty($betFlowRecords)) { // 数据为空
                    break;
                }
                // return ;
            } catch (\Exception $e) {
                // 需要将下次同步的时间加上一秒
                $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $condition->end_date)->addSeconds(1)->toDateTimeString();
                throw $e;
            }
            if (! $betFlowRecords) {
                \WLog::info('====>暂无游戏同步数据');
                // 需要将下次同步的时间加上一秒
                $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $condition->end_date)->addSeconds(1)->toDateTimeString();
                return [];
            }
            \WLog::info('====>同步VR数据');
            $lastSynchronizeTime = null;
            foreach ($betFlowRecords as $record) {
                try {
                    $playerBetFlow = new PlayerBetFlowLog();
                    $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record['playerName']);
                    if (! $playerGameAccount) {
                        throw new GameGateWayRuntimeException('该会员无法找到相应的游戏账户:' . $record['playerName']);
                    }
                    
                    $playerBetFlow->player_id = $playerGameAccount->player_id;
                    // 查询到会员id即可查询到carrier_id;
                    $playerBetFlow->carrier_id = $playerGameAccount->player->carrier_id;
                    $GameObj = new Game();
                    $game = $GameObj->where('english_game_name', $record['gameCode'])->first();
                    if (! $game) {
                        throw new GameGateWayRuntimeException('无法找到游戏码:' . $record['gameCode'] . " 对应的游戏");
                    }
                    \WLog::info('====>进入事务处理同步数据' . json_encode($record));
                    $playerBetFlow->game_id = $game['game_id'];
                    
                    // 游戏平台id，这里现在是写的假数据，后面需要修改 add by tlt
                    $playerBetFlow->game_plat_id = $game->game_plat_id;
                    $playerBetFlow->game_flow_code = $record['code'];
                    $playerBetFlow->bet_amount = $record['bet'];
                    $playerBetFlow->company_payout_amount = $record['win'];
                    $playerBetFlow->company_win_amount = $record['bet'] - $record['win'];
                    $playerBetFlow->bet_flow_available = $record['isBetAvailable'];
                    $playerBetFlow->available_bet_amount = $record['availableBet'];
                    // $playerBetFlow->player_or_banker = $record->playerOrBanker;
                    $playerBetFlow->created_at = strtotime($record['date']);
                    
                    $playerBetFlow->game_type = $record['gameCode'];
                    $playerBetFlow->game_status = $record['status'];
                    
                    if (! $lastSynchronizeTime || $lastSynchronizeTime < $record['date']) {
                        $lastSynchronizeTime = $record['date'];
                    }
                    
                    \DB::transaction(function () use ($playerGameAccount, $playerBetFlow) {
                        $condition = json_decode(json_encode($playerBetFlow), true);
                        $findData = PlayerBetFlowLog::where($condition)->first();
                        if (empty($findData)) { // 数据不存在，保存数据
                            $res = $playerBetFlow->save();
                            if ($res === false) {
                                echo "保存游戏记录失败<br/>";
                                return;
                            }
                            echo "保存游戏记录成功<br/>";
                        } else {
                            echo "游戏记录在数据库中已存在<br/>";
                        }
                    });
                    
                    \WLog::info('====>会员投注记录同步成功:', [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'date' => $record['date']
                    ]);
                } catch (\Exception $e) {
                    // var_dump('1114443333555') ;
                    \WLog::error('====>会员投注记录同步失败:', [
                        'message' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }
            $pos = strpos($lastSynchronizeTime, '.');
            if ($pos) { // 时间字符串中包含秒数,过滤秒数
                $lastSynchronizeTime = substr($lastSynchronizeTime, 0, $pos);
            }
            $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $lastSynchronizeTime)->addSeconds(1)->toDateTimeString();
        } while (1);
        return $betFlowRecords;
    }

    // @longminute 抓取多少分钟的数据
    public function getGamesRecord($start_time = null, $end_time = null, $page = 0, $page_size = 1000)
    {
        $url = self::URL . self::BETRECORD;
        $data = [
            "channelId" => - 1,
            'startTime' => $start_time,
            'endTime' => $end_time,
            'state' => - 1,
            'recordPage' => $page,
            'recordCountPerPage' => $page_size
        ];
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        $output = $this->vrCurl($url, $body);
        return $output;
    }

    public function lastSynchronizedGameFlowTimeStamp()
    {
        // 获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if (! $this->lastSynchronizeTime && \Storage::exists('VR_synchronizedTimestamp')) {
            $this->lastSynchronizeTime = \Storage::get('VR_synchronizedTimestamp');
        }
        // 如果上次的时间在28(接近30)分钟之前, 那么我们将时间强制减到20分钟 或者 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
        if (! $this->lastSynchronizeTime || $this->lastSynchronizeTime <= date('Y-m-d H:i:s', time() - 28 * 60)) {
            // 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
            $this->lastSynchronizeTime = date('Y-m-d H:i:s', time() - 20 * 60);
        }
        return $this->lastSynchronizeTime;
    }

    // 查询余额
    public function checkBalance($accountUserName)
    {
        $url = self::URL . self::BALANCE;
        
        $data = [
            "playerName" => $accountUserName
        ];
        
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        return $this->vrCurl($url, $body);
    }

    // 新增玩家帳戶
    public function createMember($pre)
    {
        $playeraccount = $pre . 'Z' . $this->generate_value(6);
        
        $url = self::URL . self::CREATEUSER;
        $data = [
            "playerName" => $playeraccount
        ];
        
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        if ($this->vrCurl($url, $body) != false) {
            return $playeraccount;
        } else {
            return false;
        }
        ;
    }

    // 踢线
    public function kick($accountUserName)
    {
        $url = self::URL . self::KICKUSER;
        $data = [
            "playerName" => $accountUserName
        ];
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        return $this->vrCurl($url, $body);
    }

    // 交易记录查询
    public function transferRecord($accountUserName)
    {
        $url = self::URL . self::TRANSACTIONRECORD;
        $data = [
            "playerName" => $accountUserName
        ];
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        return $this->vrCurl($url, $body);
    }

    // 游戏转出
    public function transferOut($accountUserName, $money)
    {
        $serialNumber = date('YmdHis', time()) . mt_rand(0, 1000);
        $data = array(
            'serialNumber' => $serialNumber,
            'createTime' => gmdate("Y-m-d\TH:i:s\Z"),
            'amount' => $money,
            'playerName' => $accountUserName,
            'type' => 1
        );
        
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        $url = self::URL . self::TRANSACTION;
        
        $output = $this->vrCurl($url, $body);
        if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        } else if ($output && isset($output['state']) && $output['state'] == 0) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        }
    }

    // 游戏转入
    public function transferIn($accountUserName, $money)
    {
        $serialNumber = date('YmdHis', time()) . mt_rand(0, 1000);
        $data = array(
            'serialNumber' => $serialNumber,
            'createTime' => gmdate("Y-m-d\TH:i:s\Z"),
            'amount' => $money,
            'playerName' => $accountUserName,
            'type' => 0
        );
        
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        $url = self::URL . self::TRANSACTION;
        
        $output = $this->vrCurl($url, $body);
        if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        } else if ($output && isset($output['state']) && $output['state'] == 0) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $serialNumber
            );
        }
    }

    // 玩家登入
    public function login($playerName, $channelId = NULL)
    {
        $url = self::URL . self::LOGIN;
        
        $data = "playerName=" . $playerName . "&loginTime=" . gmdate("Y-m-d\TH:i:s\Z");
        
        if (! is_null($channelId)) {
            $data .= "&channelId=" . $channelId;
        }
        
        $encrypt_data = urlencode($this->apiEncode($data));
        
        $loginUrl = $url . "?version=" . self::VERSION . "&id=" . self::MERCHANTID . "&data=" . $encrypt_data;
        
        echo "<script>window.location.href ='" . $loginUrl . "';</script>";
        // return $loginUrl;
    }

    // 加密
    private function apiEncode($data)
    {
        // Pad for PKCS7
        /*
         * $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
         * $len = strlen($data);
         * $pad = $blockSize - ($len % $blockSize);
         * $data .= str_repeat(chr($pad), $pad);
         *
         * //Encrypt data
         * $encData = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::KEY, $data, MCRYPT_MODE_ECB);
         */
        $cipher = 'AES-256-ECB';
        // $ivlen = openssl_cipher_iv_length($cipher);
        // $iv = openssl_random_pseudo_bytes($ivlen);
        $encData = openssl_encrypt($data, $cipher, self::KEY, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

    // 解密
    private function apiDecode($base64_data)
    {
        $data = base64_decode($base64_data);
        
        // Decrypt data
        /*
         * $plain_data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::KEY, $data, MCRYPT_MODE_ECB);
         *
         * // Remove US-ASCII control character
         * $plain_data = trim($plain_data, "\x00..\x1F");
         */
        $cipher = 'AES-256-ECB';
        // $ivlen = openssl_cipher_iv_length($cipher);
        // $iv = openssl_random_pseudo_bytes($ivlen);
        $plain_data = openssl_decrypt($data, $cipher, self::KEY, OPENSSL_RAW_DATA);
        
        return $plain_data;
    }
}