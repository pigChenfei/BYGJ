<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

use App\Vendor\Game\Imp\GameAbstract;

class TTG extends GameAbstract
{

    const AFFILIATEID = 'TTC';

    const AFFILIATELOGIN = 'TTC0988786';

    // 正式URL
    const API_URL = 'https://ams2-api.stg.ttms.co:8443';

    // 抓单地址
    const API_URL2 = 'https://ams2-df.stg.ttms.co:8443/dataservice';

    // 测试URL
    // const API_URL = 'https://ams2-api.stg.ttms.co:7443';
    
    // 测试数据url
    // const API_URL2 = 'https://ams2-df.ttms.co:7443/dataservice';
    
    // 登录注册
    const API_LOGIN = self::API_URL . '/cip/gametoken/';

    // 帐号存在判断
    const API_EXISTS = self::API_URL . '/cip/player/TTC/existence';

    // 查询余额
    const API_BALANCE = self::API_URL . '/cip/player/TTC/balance';

    // 转帐
    const API_TRANSATION = self::API_URL . '/cip/transaction/TTC/';

    // 抓单
    const API_BETRECORD = self::API_URL2 . '/datafeed/transaction/current';

    public function ttgCurl($url, $param, $method = 'post')
    {
        $header = array();
        $header[] = "Content-Type: application/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // post data
        } else if ($method == 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // delete data
        } else {
            $str = '';
            foreach ($param as $key => $v) {
                $str .= $key . '=' . $v . '&';
            }
            $url = $url . '?' . rtrim($str, '&');
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            return false;
        } else {
            return $output;
        }
    }

    public function ttgdataCurl($url, $param, $method = 'post')
    {
        $header = array();
        $header[] = "T24-Affiliate-Login: " . self::AFFILIATELOGIN;
        $header[] = "T24-Affiliate-Id: " . self::AFFILIATEID;
        $header[] = "Content-Type: text/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // post data
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        var_dump($output);
        if ($error) {
            return false;
        } else {
            return $output;
        }
    }

    public function betRecord()
    {
        $url = self::API_BETRECORD;
        $startDate = '20180115';
        $endDate = '20180115';
        $requestId = 'TTC_1ZbpRlul';
        $param = "<searchdetail requestId='" . $requestId . "''>" . "<daterange startDate='" . $startDate . "'' startDateHour='9' startDateMinute='10' endDate='" . $endDate .
             "' endDateHour='9' endDateMinute='15'/>" . "<account currency='CNY' />" . "<partner partnerId='" . self::AFFILIATEID . "' includeSubPartner='Y' />" .
             "<transaction transactionType='Game' transactionSubType='Wager' />" . "</searchdetail>";
        $this->ttgdataCurl($url, $param);
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

    // 创建用户
    public function createMember($pre)
    {
        $playeraccount = '';
        $token = '';
        do {
            $exist = 0;
            $playeraccount = $pre . 'Z' . $this->generate_value(6);
            $url = str_replace("TTC", $playeraccount, self::API_EXISTS);
            $param = array(
                'uid' => $playeraccount
            );
            $output = $this->ttgCurl($url, $param, 'get');
            if ($output) {
                $xmloutput = (array) simplexml_load_string($output);
                $exist = $xmloutput['@attributes']['exist'];
                if (! $exist) {
                    $token = $this->login($playeraccount);
                }
            }
        } while ($exist);
        if ($token) {
            return array(
                'username' => $playeraccount,
                'token' => $token
            );
        } else {
            return false;
        }
    }

    // 登录用户
    public function login($accountUserName, $channelId = NULL)
    {
        $url = self::API_LOGIN . $accountUserName;
        $param = '<logindetail><player account="CNY" country="CN" firstName="" lastName="" userName="" nickName="" tester="0" partnerId="' . self::AFFILIATEID .
             '" commonWallet="0" /><partners><partner partnerId="zero" partnerType="0" /><partner partnerId="Everfriend" partnerType="1" /><partner partnerId="' . self::AFFILIATEID .
             '" partnerType="1" /></partners></logindetail>';
        $output = $this->ttgCurl($url, $param);
        if ($output) {
            $xmloutput = (array) simplexml_load_string($output);
            if (isset($xmloutput['@attributes']['token'])) {
                $token = $xmloutput['@attributes']['token'];
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // 查询余额
    public function checkBalance($accountUserName)
    {
        $url = str_replace("TTC", $accountUserName, self::API_BALANCE);
        $param = array(
            'uid' => $accountUserName
        );
        $output = $this->ttgCurl($url, $param, 'get');
        if ($output) {
            $xmloutput = (array) simplexml_load_string($output);
            if (isset($xmloutput['@attributes']['real'])) {
                return $xmloutput['@attributes']['real'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // 转入游戏平台，金额正负区分转入转出
    public function transferIn($accountUserName, $money)
    {
        $orederId = 'TTC_' . time() . $this->generate_value(2);
        $url = self::API_TRANSATION . $orederId;
        $param = '<transactiondetail uid="' . $accountUserName . '" amount="' . $money . '" />';
        $output = $this->ttgCurl($url, $param);
        if ($output) {
            $xmloutput = (array) simplexml_load_string($output);
            if (isset($xmloutput['@attributes']['retry']) && $xmloutput['@attributes']['retry'] == 0) {
                return array(
                    'transactionOrderId' => $orederId,
                    'success' => true
                );
            } else {
                return array(
                    'transactionOrderId' => $orederId,
                    'success' => false
                );
            }
        } else {
            return array(
                'transactionOrderId' => $orederId,
                'success' => 'unknown'
            );
        }
    }

    // 转出游戏平台，金额正负区分转入转出
    public function transferOut($accountUserName, $money)
    {
        return $this->transferIn($accountUserName, - $money);
    }

    // 进入游戏
    public function playgameH5($token, $gamename, $gametype, $gameid)
    {
        $url = 'http://ams-games.stg.ttms.co/casino/default/game/game.html?playerHandle=' . $token . '&account=CNY&gameName=' . $gamename . '&gameType=' . $gametype . '&gameId=' .
             $gameid . '&lang=zh-cn&deviceType=web&lsdId=TTC';
        echo "<script>window.location.href ='" . $url . "'</script>";
    }
}