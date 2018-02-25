<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

use App\Vendor\Game\Imp\GameAbstract;

class PT extends GameAbstract
{

    const API_HOST = 'https://kioskpublicapi.luckyspin88.com/';

    const ENTITYKEY = 'd97ddafeeb4f82adc72ae4e558cc292bd23ad85c229600c5c6481876b472a964a47dd93879df24b350c38bc3762d86f1f1a6244e1dc11c2b24003e80decbb3f4';

    const KIOSKNAME = 'TTC';

    const ADMINNAME = 'TTCADMIN';

    // 创建用户
    const API_PLAYER_CREATE = 'player/create';

    // 查询余额
    const API_PLAYER_BALANCE = 'player/balance';

    // 转出游戏
    const API_PLAYER_WITHDRAW = 'player/withdraw';

    // 转入游戏
    const API_PLAYER_DEPOSIT = 'player/deposit';

    // 游戏踢线
    const API_PLAYER_LOGOUT = 'player/logout';

    // 用户信息更新
    const API_PLAYER_UPDATE = 'player/update';

    // 抓取下注单数据
    const API_PLAYER_GAMES_BETTING_FLOW = 'customreport/getdata/reportname/PlayerGames';

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

    public function playgameH5($accountUserName, $password, $gamecode)
    {
        echo '<html><header><title></title>' . '<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>' . '<script type="text/javascript">' . 'iapiSetCallout("Login", calloutLogin);' . 'iapiLogin("' . $accountUserName . '", "' . $password . '", 1, "ch");' . 'var requestId = iapiRequestIds[0][0];' . 'function calloutLogin(response) {' . 'if(response.errorCode == 0){' . 'window.location.href = "http://cache.download.banner.greenjade88.com/casinoclient.html?language=ZH-CN&game=' . $gamecode . '";' . '}' . '}' . '</script></header><body></body></html>';
    }

    /**
     * @longminute 抓取多少分钟的数据,最长只能抓取10分种数据
     */
    public function betRecord($longminute)
    {
        $url = self::API_HOST . self::API_PLAYER_GAMES_BETTING_FLOW;
        // $time = time()-43200;
        $time = time();
        
        $param = array(
            'showinfo' => 1,
            'frozen' => 'all',
            'perPage' => 50000,
            'page' => 1
        );
        $param = $this->timeInterval($param, $time, $longminute);
        $result = $this->ptMorePage($url, $param);
        return $result;
    }

    /**
     *
     * @param
     *            参数
     *            @抓取多页数据
     */
    private function ptMorePage($url, $param)
    {
        $totalresult = array();
        $bool = true;
        do {
            $result = $this->ptCurl($url, $param);
            $res = array();
            
            if ($result && $result['result'] && count($result['result'])) {
                foreach ($result['result'] as $r) {
                    
                    preg_match('/\(\w+\)/', $r['GAMENAME'], $matches);
                    
                    $res['gameAccount'] = $r['PLAYERNAME'];
                    $res['gameCode'] = substr($matches[0], 1, strlen($matches[0]) - 2);
                    $res['orderId'] = $r['GAMECODE'];
                    $res['betAmount'] = $r['BET'];
                    $res['orderDate'] = $r['GAMEDATE'];
                    $res['payOff'] = $r['WIN'] - $r['BET'];
                    $res['isPaid'] = 1;
                    $res['commissionable'] = $r['BET'];
                    if ($r['BET'] == 0 && $r['WIN']) {
                        $res['isEffective'] = 0;
                    } else {
                        $res['isEffective'] = 1;
                    }
                    array_push($totalresult, $res);
                }
                $param['page'] = $param['page'] + 1;
            }
            if (! $result || ! $result['result'] || $result['pagination']['totalPages'] == 1 || $result['pagination']['totalPages'] == 0 || ($result['pagination']['currentPage'] == $result['pagination']['totalPages'])) {
                $bool = false;
            }
        } while ($bool);
        return $totalresult;
    }

    /*
     * @isdate 是否只需精确到天
     * @longminute 抓取多长时间的数据
     * @time 当前时间
     */
    private function timeInterval($param, $time, $longminute)
    {
        $longminutetime = $longminute * 60;
        
        // 当天凌晨的时间戳
        $dawn = strtotime(date('Y-m-d', $time));
        
        if ($time - $longminutetime >= $dawn) {
            // 查询时长不跨天
            $param['startdate'] = urlencode(date("Y-m-d H:i:s", $time - $longminutetime));
            $param['enddate'] = urlencode(date("Y-m-d H:i:s", $time));
        } else {
            // 查询时长跨天
            $param['startdate'] = urlencode(date("Y-m-d H:i:s", $dawn));
            $param['enddate'] = urlencode(date("Y-m-d H:i:s", $time));
        }
        return $param;
    }

    /**
     * 用户信息更新
     * @result更新用户密码
     */
    public function memberPasswordUpdate($accountUserName, $password)
    {
        $url = self::API_HOST . self::API_PLAYER_UPDATE;
        $param = array(
            'playername' => strtoupper($accountUserName)
        );
        $result = $this->ptCurl($url, $param);
        return true;
    }

    /**
     * 游戏踢人
     * @result转出后游戏余额
     */
    public function kick($accountUserName)
    {
        $url = self::API_HOST . self::API_PLAYER_LOGOUT;
        $param = array(
            'playername' => strtoupper($accountUserName)
        );
        $this->ptCurl($url, $param);
        return true;
    }

    /**
     * 转出帐号
     * @result转出后游戏余额
     */
    public function transferOut($accountUserName, $money)
    {
        $url = self::API_HOST . self::API_PLAYER_WITHDRAW;
        $param = array(
            'playername' => strtoupper($accountUserName),
            'amount' => $money,
            'adminname' => self::ADMINNAME
        );
        $result = $this->ptCurl($url, $param);
        if ($result && isset($result['result'])) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $result['result']['ptinternaltransactionid'],
                'currentbalance' => $result['result']['currentplayerbalance']
            );
        } else if (! $result) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money
            );
        }
    }

    /**
     * 转入帐号
     * @
     */
    public function transferIn($accountUserName, $money)
    {
        $url = self::API_HOST . self::API_PLAYER_DEPOSIT;
        $param = array(
            'playername' => strtoupper($accountUserName),
            'amount' => $money,
            'adminname' => self::ADMINNAME
        );
        $result = $this->ptCurl($url, $param);
        if ($result && isset($result['result'])) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $result['result']['ptinternaltransactionid'],
                'currentbalance' => $result['result']['currentplayerbalance']
            );
        } else if (! $result) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money
            );
        }
    }

    /**
     * @accountUserName 查询帐户余额,帐户必须转成大写
     *
     * @return 查询失败返回false,
     */
    public function checkBalance($accountUserName)
    {
        $url = self::API_HOST . self::API_PLAYER_BALANCE;
        $param = array(
            'playername' => strtoupper($accountUserName)
        );
        $result = $this->ptCurl($url, $param);
        var_dump($result);
        if ($result && isset($result['result'])) {
            return $result['result']['balance'];
        } else {
            return false;
        }
    }

    /**
     * @pre 前辍
     * @game 创建成功返回用户名和密码
     *
     * @return 创建失败返回false,
     */
    public function createMember($pre)
    {
        $url = self::API_HOST . self::API_PLAYER_CREATE;
        $playername = $pre . 'Z' . $this->generate_value(6);
        $param = array(
            'playername' => $playername,
            'adminname' => self::ADMINNAME,
            'kioskname' => self::KIOSKNAME
        );
        $result = $this->ptCurl($url, $param);
        if ($result && isset($result['result'])) {
            return array(
                'username' => $playername,
                'password' => $result['result']['password']
            );
        } else {
            return false;
        }
    }

    public function ptCurl($url, $param)
    {
        $header = [
            "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Cache-Control: max-age=0",
            "Connection: keep-alive",
            "Keep-Alive:timeout=5, max=100",
            "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3",
            "Accept-Language:es-ES,es;q=0.8",
            "Pragma: ",
            "X_ENTITY_KEY:" . self::ENTITYKEY
        ];
        
        foreach ($param as $key => $value) {
            $url .= '/' . $key . '/' . $value;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, app_path('Vendor/Game/PT.pem'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLKEY, app_path('Vendor/Game/PT.key'));
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        if ($output === false) {
            return false;
        } else {
            return json_decode($output, true);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Vendor\Game\Imp\GameImp::login()
     */
    public function login($playerName, $channelId = NULL)
    {
        // TODO Auto-generated method stub
    }
}