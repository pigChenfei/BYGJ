<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

use Curl\Curl;

class OneWorks
{

    // 代理代码
    const OPCODE = 'TTCBET';

    const SECURITY = 'HUZMZJSHJEV568AQ';

    const API_URL = 'http://api.prod.ib.gsoft88.net';

    // 体育博彩
    // const API_MKI = 'http://mkt.ib.ttc.bet/Deposit_ProcessLogin.aspx';
    
    // 创建用户
    const API_CREATEMEMBER = '/api/CreateMember';

    // 用户登录
    const API_LOGIN = '/api/Login';

    // 查询余额
    const API_CHECKUSERBALANCE = '/api/CheckUserBalance';

    // 转帐
    const API_FUNDTRANSFER = '/api/FundTransfer';

    // 订单查询
    const API_CHECKFUNDTRANSFER = '/api/CheckFundTransfer';

    // 查询订单
    const API_GETSPORTBETTINGDETAIL = '/api/GetSportBettingDetail';

    // 踢人
    const API_KICKUSER = '/api/KickUser';

    public function API_MKI()
    {
        $api = $_SERVER['HTTP_HOST'];
        if (strpos($_SERVER['HTTP_HOST'], 'www.') !== false) {
            $api = str_replace('www.', '', $api);
        }
        return 'http://mkt.ib.' . $api . '/Deposit_ProcessLogin.aspx';
    }

    /**
     * 检查订单状态
     * 返回值0=执行成功，1=执行失败，2=挂起
     */
    public function checkTransfer($accountUserName, $transid)
    {
        $param = array(
            'OpCode' => self::OPCODE,
            'Playername' => $accountUserName,
            'OpTransId' => $transid
        );
        $output = $this->onworksCurl(self::API_CHECKFUNDTRANSFER, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return $output['Data']['status'];
        } else {
            return false;
        }
    }

    /**
     * @longminute 抓取多少分钟的数据,最长为720分钟
     * @isdate 是否只精确到天
     */
    public function betRecord($longminute)
    {
        // 时间调整为12个小时
        $time = time() - 43200;
        $longminutetime = $longminute * 60;
        $dawn = strtotime(date('Y-m-d', $time));
        
        $param = array(
            'OpCode' => self::OPCODE
        );
        if ($time - $dawn >= $longminutetime) {
            $param['StartTime'] = date('Y-m-d H:i:s', $time - $longminutetime);
            $param['EndTime'] = date('Y-m-d H:i:s', $time);
        } else {
            $param['StartTime'] = date('Y-m-d H:i:s', $dawn);
            $param['EndTime'] = date('Y-m-d H:i:s', $time);
        }
        $result = $this->onworksCurl(OneWorks::API_GETSPORTBETTINGDETAIL, $param, 1);
        if ($result && $result['error_code'] == 0) {
            $totalresult = array();
            foreach ($result['Data'] as $r) {
                $res['gameAccount'] = $r['PlayerName'];
                $res['orderId'] = $r['TransId'];
                $res['gameType'] = $r['SportType'];
                $res['betAmount'] = $r['Stake'];
                $res['orderDate'] = $r['TransactionTime'];
                $res['isEffective'] = 1;
                $res['payOff'] = $r['WinLoseAmount'];
                $res['commissionable'] = $r['Stake'];
                if ($r['TicketStatus'] == 'WON' || $r['TicketStatus'] == 'LOSE') {
                    $res['isPaid'] = 1;
                } else {
                    $res['isPaid'] = 0;
                }
                array_push($totalresult, $res);
            }
            return $totalresult;
        } else {
            return false;
        }
    }

    /**
     * 进行游戏大厅
     */
    public function loginhall($accountUserName)
    {
        $this->playgameH5($accountUserName);
    }

    /**
     * 进行游戏
     */
    public function playgameH5($accountUserName)
    {
        $api = $_SERVER['HTTP_HOST'];
        if (strpos($_SERVER['HTTP_HOST'], 'www.') !== false) {
            $api = str_replace('www.', '', $api);
        }
        $token = $this->login($accountUserName);
        $param = array(
            'g' => $token,
            'lang' => 'cs',
            'OType' => '2'
        );
        setcookie("g", $param['g'], time() + 3600, '/', '.' . $api);
        unset($param['g']);
        echo '<script> location.href="' . $this->API_MKI() . '?' . http_build_query($param) . '";</script>';
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
     * @accountUserName 平台游戏帐号
     * @踢用户下线
     */
    public function kick($accountUserName)
    {
        $param = array(
            'OpCode' => self::OPCODE,
            'Playername' => $accountUserName
        );
        $output = $this->onworksCurl(self::API_KICKUSER, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @金额转入游戏平台
     *
     * @return 导常返回false,否则还回数组
     */
    public function transferOut($accountUserName, $money)
    {
        $OpTransId = time() . mt_rand(10000000, 99999999);
        $param = array(
            'OpCode' => self::OPCODE,
            'PlayerName' => $accountUserName,
            'amount' => $money,
            'OpTransId' => $OpTransId,
            'direction' => 0
        );
        $output = $this->onworksCurl(self::API_FUNDTRANSFER, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        } else if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @金额转入游戏平台
     *
     * @return 导常返回false,否则还回数组
     */
    public function transferIn($accountUserName, $money)
    {
        $OpTransId = time() . mt_rand(10000000, 99999999);
        $param = array(
            'OpCode' => self::OPCODE,
            'PlayerName' => $accountUserName,
            'amount' => $money,
            'OpTransId' => $OpTransId,
            'direction' => 1
        );
        $output = $this->onworksCurl(self::API_FUNDTRANSFER, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        } else if (! $output) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $OpTransId
            );
        }
    }

    /**
     * @ 创建用户
     * @pre 帐号名前辍
     */
    public function createMember($pre, $oddstype = 2, $maxtransfer = 500000, $mintransfer = 1)
    {
        $playeraccount = $pre . 'Z' . $this->generate_value(6);
        $param = array(
            'OpCode' => self::OPCODE,
            'PlayerName' => $playeraccount,
            'OddsType' => $oddstype,
            'MaxTransfer' => $maxtransfer,
            'MinTransfer' => $mintransfer
        );
        $output = $this->onworksCurl(self::API_CREATEMEMBER, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return $plyeraccount;
        } else {
            return false;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @查询余额
     *
     * @return 导常返回false,否则还回金额
     */
    public function checkBalance($accountUserName)
    {
        $param = array(
            'OpCode' => self::OPCODE,
            'PlayerName' => $accountUserName
        );
        $output = $this->onworksCurl(OneWorks::API_CHECKUSERBALANCE, $param, 1);
        if ($output && $output['error_code'] == 0) {
            return $output['Data'][0]['balance'];
        } else {
            return false;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @game 游戏相关信息
     *
     * @return 登录失败返回false,否则返回token
     */
    public function login($accountUserName)
    {
        $param = array(
            'OpCode' => self::OPCODE,
            'PlayerName' => $accountUserName
        );
        $output = $this->onworksCurl(OneWorks::API_LOGIN, $param, 1);
        if ($output['error_code'] === 0) {
            return $output['sessionToken'];
        } else {
            return false;
        }
    }

    // $param为键值对数组
    private function onworksCurl($url, $param, $hasSecurityToken = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($hasSecurityToken) {
            curl_setopt($ch, CURLOPT_URL,
                self::API_URL . $url . '?SecurityToken=' .
                     strtoupper(md5(self::SECURITY . $url . '?' . str_replace('%3A', '%3a', http_build_query($param)))) .
                     '&' . http_build_query($param));
        } else {
            $url = self::API_URL . $url;
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($param));
        }
        $output = curl_exec($ch);
        
        if ($output === false) {
            return false;
        } else {
            return json_decode($output, true);
        }
    }
}