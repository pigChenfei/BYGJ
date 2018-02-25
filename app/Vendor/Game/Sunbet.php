<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/11/28
 * Time: 13:16
 */
namespace App\Vendor\Game;

use App\Vendor\Game\Imp\GameAbstract;

class Sunbet extends GameAbstract
{

    // TOKEN创建
    const API_TOKEN = 'https://tgpaccess.com/api/oauth/token';

    // 用户登录或注册
    const API_LOGIN = 'https://tgpaccess.com/api/player/authorize';

    // 游戏踢人
    const API_KICK = 'https://tgpaccess.com/api/player/deauthorize';

    // 查询余额
    const API_BALANCE = 'https://tgpaccess.com/api/player/balance';

    // 进入游戏
    const API_GAME = 'https://tgpasia.com/gamelauncher';

    // 游戏平台转出
    const API_DEBIT = 'https://tgpaccess.com/api/wallet/debit';

    // 游戏平台转入
    const API_CREDIT = 'https://tgpaccess.com/api/wallet/credit';

    // 查询投注纪录
    const API_HISTORY = 'https://tgpaccess.com/api/history/bets';

    // 申博移动端游戏
    const API_SBMOBILE = 'https://tgpasia.com/SBmlobby';

    private function sbCurl($url, $body = '', $method = 'post', $isoriginal = false)
    {
        $access_token = $this->auth();
        if (! $access_token) {
            return false;
        }
        $header[] = 'Authorization: Bearer ' . $access_token;
        $header[] = 'Content-Type: application/json';
        $header[] = 'Accept: application/json';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        if (! $output) {
            return false;
        } else {
            
            return $isoriginal ? $output : json_decode($output, true);
        }
    }

    public function auth()
    {
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        $data = "grant_type=client_credentials&scope=playerapi&client_id=ttc1122&client_secret=4LzVCYF8psVIbaog1LP2vM5SYZxAMmoto0hpkJSR2sy";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        if (! $output) {
            return false;
        } else {
            $output = json_decode($output, true);
            return $output['access_token'];
        }
    }

    /*
     * 创建用户
     */
    public function createMember($accountUserName)
    {
        return $this->login($accountUserName);
    }

    /*
     * 用户授权登录
     */
    public function login($accountUserName, $channelId = NULL)
    {
        $url = self::API_LOGIN;
        $param = array(
            'ipaddress' => '47.52.246.121',
            'username' => $accountUserName,
            'userid' => $accountUserName,
            'lang' => 'zh-CN',
            'cur' => 'RMB',
            'betlimitid' => 3, // 玩家的VIP等级，用来设置投注限制。
            'platformtype' => 1,
            'istestplayer' => false
        );
        $output = $this->sbCurl($url, json_encode($param));
        if (! $output) {
            return false;
        } else {
            return array(
                'username' => $accountUserName,
                'authtoken' => $output['authtoken']
            );
        }
    }

    /*
     * 踢线
     */
    public function kick($accountUserName)
    {
        $url = self::API_KICK;
        $output = $this->sbCurl($url, json_encode(array(
            'userid' => $accountUserName
        )));
        if ($output && $output['success']) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 检测帐号余额
     */
    public function checkBalance($accountUserName)
    {
        $url = self::API_BALANCE . '?userid=' . $accountUserName . "&cur=RMB";
        $output = $this->sbCurl($url, '', 'get');
        if ($output && isset($output['bal'])) {
            return $output['bal'];
        } else {
            return false;
        }
    }

    /*
     * 进入游戏
     * @providercode 平台代码
     * @gcode 游戏代码
     */
    public function playgameH5($accountUserName, $providercode, $gcode)
    {
        $login = $this->login($accountUserName);
        if (! $login)
            return false;
        $url = self::API_GAME . "?gpcode=" . $providercode . "&gcode=" . $gcode . "&platform=0&token=" . $login['authtoken'];
        // 这里根据url地址直接跳转到游戏界面
        Header("Location:$url");
    }

    /**
     * 玩移动端游戏
     */
    public function playgameSBmobile($accountUserName, $providercode, $gcode)
    {
        $login = $this->login($accountUserName);
        if (! $login)
            return false;
        $url = self::API_SBMOBILE . "?gpcode=" . $providercode . "&gcode=" . $gcode . "&platform=0&token=" . $login['authtoken'];
        // 这里根据url地址直接跳转到游戏界面
        Header("Location:$url");
    }

    /**
     * 转出游戏帐号
     */
    public function transferOut($accountUserName, $money)
    {
        $ordierId = date('YmdHis', time()) . mt_rand(0, 1000);
        $timestamp = date('Y-m-d H:i:s', time());
        $url = self::API_DEBIT;
        
        $param = array(
            'userid' => $accountUserName,
            'amt' => $money,
            'cur' => 'RMB',
            'txid' => $ordierId, // 订单号
            'timestamp' => $timestamp,
            'desc' => null
        );
        $output = $this->sbCurl($url, json_encode($param));
        var_dump($output);
        exit();
        if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        } else if ($output && isset($output['bal'])) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        }
    }

    /**
     * 转入游戏帐号
     */
    public function transferIn($accountUserName, $money)
    {
        $ordierId = date('YmdHis', time()) . mt_rand(0, 1000);
        $timestamp = date('Y-m-d H:i:s', time());
        $url = self::API_CREDIT;
        
        $param = array(
            'userid' => $accountUserName,
            'amt' => $money,
            'cur' => 'RMB',
            'txid' => $ordierId, // 订单号
            'timestamp' => $timestamp,
            'desc' => null
        );
        $output = $this->sbCurl($url, json_encode($param));
        
        if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        } else if ($output && isset($output['bal'])) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $ordierId
            );
        }
    }

    /**
     * 查询下单流水
     */
    public function betRecord($longminute)
    {
        $time = time();
        $longminutetime = $longminute * 60;
        $dawn = strtotime(date('Y-m-d', $time));
        $startdate = '';
        $enddate = '';
        if ($time - $dawn >= $longminutetime) {
            $startdate = str_replace(' ', 'T', date('Y-m-d H:i:s', $time - $longminutetime));
            $enddate = str_replace(' ', 'T', date('Y-m-d H:i:s', $time));
        } else {
            $startdate = str_replace(' ', 'T', date('Y-m-d H:i:s', $dawn));
            $enddate = str_replace(' ', 'T', date('Y-m-d H:i:s', $time));
        }
        
        $url = self::API_HISTORY . '?startdate=' . $startdate . '&enddate=' . $enddate;
        $output = $this->sbCurl($url, '', 'get', true);
        if ($output && substr($output, 0, 8) == 'ugsbetid') {
            return $this->csvToArray($output, 28);
        } else {
            return false;
        }
    }

    /**
     * csv转数组
     */
    public function csvToArray($resData)
    {
        $str = 'ugsbetid,txid,betid,beton,betclosedon,betupdatedon,timestamp,roundid,roundstatus,userid,username,riskamt,winamt,winloss,beforebal,postbal,cur,gameprovider,gameprovidercode,gamename,gameid,platformtype,ipaddress,bettype,playtype,playertype,turnover,validbet';
        
        $data = trim(str_replace($str, '', $resData));
        if (empty($data)) {
            return false;
        }
        $content = explode(',', $data);
        $rkey = explode(',', $str);
        $arr = array();
        $totalresult = array();
        foreach ($content as $key => $v) {
            if ($key > 0 && ($key % 27 == 0)) {
                $pos = strpos($content[$key], '.');
                $arr[] = substr($content[$key], 0, $pos + 4);
                $value = trim(substr($content[$key], $pos + 4));
                if (! empty($value)) {
                    $arr[] = $value;
                }
            } else {
                $arr[] = $v;
            }
        }
        $KeyValue = array();
        for ($i = 0, $j = 0; $i < count($arr); $i ++, $j ++) {
            if ($j == 28) {
                $j = 0;
                array_push($KeyValue, $a);
            }
            $a[$rkey[$j]] = $arr[$i];
            if (($i + 1) == count($arr)) {
                array_push($KeyValue, $a);
            }
        }
        foreach ($KeyValue as $r) {
            $res['isPaid'] = $r['roundstatus'] == "Closed" ? 1 : 0;
            $res['gameAccount'] = $r['userid'];
            $res['orderId'] = $r['txid'];
            $res['gamePlat'] = $r['gameprovidercode'];
            $res['gameCode'] = str_replace(' ', '_', $r['gamename']);
            $res['betAmount'] = abs($r['riskamt']);
            $res['orderDate'] = str_replace('T', '', substr($r['beton'], 0, strpos($r['beton'], '+')));
            $res['isEffective'] = abs($r['riskamt']) == 0 ? 0 : 1;
            $res['payOff'] = $r['winloss'] < 0 ? 0 : $r['winloss'];
            $res['commissionable'] = abs($r['riskamt']) == 0 ? 0 : abs($r['riskamt']);
            
            array_push($totalresult, $res);
        }
        
        return $totalresult;
    }
}