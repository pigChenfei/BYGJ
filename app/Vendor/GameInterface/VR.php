<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

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

    // @longminute 抓取多少分钟的数据
    public function betRecord($longminute)
    {
        $url = self::URL . self::BETRECORD;
        
        $time = time();
        $longminutetime = $longminute * 60;
        
        $data = [
            "channelId" => - 1,
            'startTime' => gmdate("Y-m-d\TH:i:s\Z", $time - $longminutetime + 8 * 3600),
            'endTime' => gmdate("Y-m-d\TH:i:s\Z", $time + 8 * 3600),
            'state' => - 1,
            'recordPage' => 0,
            'recordCountPerPage' => 100
        ];
        $body = [
            "version" => self::VERSION,
            "id" => self::MERCHANTID,
            "data" => $this->apiEncode(json_encode($data))
        ];
        
        return $this->vrCurl($url, $body);
    }

    /**
     *
     * @param
     *            参数
     *            @抓取多页数据
     */
    private function ptMorePage($url)
    {
        $totalresult = array();
        $bool = true;
        $page = 1;
        do {
            $purl = $url . '&page=' . $page;
            $result = $this->mgCurl($purl, '', 'get');
            $res = array();
            
            if ($result && $result['data'] && count($result['data'])) {
                foreach ($result['data'] as $r) {
                    $res['gameAccount'] = $r['account_ext_ref'];
                    $res['gameId'] = $r['meta_data']['item_id'];
                    $res['orderId'] = $r['meta_data']['round_seq_id'];
                    $res['betAmount'] = $r['sum_of_wager'];
                    $res['orderDate'] = $r['start_time'];
                    $res['payOff'] = $r['sum_of_payout'] > 0 ? $r['sum_of_payout'] - $r['sum_of_wager'] : 0;
                    $res['isPaid'] = $r['status'] == "CLOSED" ? 1 : 0;
                    
                    // sum_of_refund 退费，可能会依网络问题造成投注失败
                    if ($r['sum_of_refund'] > 0) {
                        $res['isEffective'] = 0; // 无效投注
                        $res['commissionable'] = 0;
                    } else {
                        $res['isEffective'] = 1; // 有效投注
                        $res['commissionable'] = $r['sum_of_wager']; // 有效投注额
                    }
                    
                    array_push($totalresult, $res);
                }
                $page ++;
            }
            if (! $result || ! count($result['data'])) {
                $bool = false;
            }
        } while ($bool);
        return $totalresult;
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