<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\GameGateway\OneWorks;

use Curl\Curl;

class OneWorks
{

    // 代理代码
    const OPCODE = 'TTCBET';

    const SECURITY = 'HUZMZJSHJEV568AQ';

    const API_URL = 'http://api.prod.ib.gsoft88.net';

    // hello例子
    const API_HELLO = '/api/hello';

    // 创建用户
    const API_CREATEMEMBER = '/api/CreateMember';

    // 用户登录
    const API_LOGIN = '/api/Login';

    // 查询余额
    const API_CHECKUSERBALANCE = '/api/CheckUserBalance';

    // 转帐
    const API_FUNDTRANSFER = '/api/FundTransfer';

    // 查询订单
    const API_GETSPORTBETTINGDETAIL = '/api/GetSportBettingDetail';

    // 踢人
    const API_KICKUSER = '/api/KickUser';

    // 体育博彩
    // const API_MKI = 'http://mkt.ib.ttc.bet/Deposit_ProcessLogin.aspx';
    public $API_MKI;

    public $ch;

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function API_MKI()
    {
        $api = $_SERVER['HTTP_HOST'];
        if (strpos($_SERVER['HTTP_HOST'], 'www.') !== false) {
            $api = str_replace('www.', '', $api);
        }
        return 'http://mkt.ib.' . $api . '/Deposit_ProcessLogin.aspx';
    }

    // $param为键值对数组
    function remoteApi($url, $param, $hasSecurityToken = 0)
    {
        if ($hasSecurityToken) {
            curl_setopt($this->ch, CURLOPT_URL,
                self::API_URL . $url . '?SecurityToken=' .
                     strtoupper(md5(self::SECURITY . $url . '?' . http_build_query($param))) . '&' .
                     http_build_query($param));
            
            $test = self::API_URL . $url . '?SecurityToken=' .
                 strtoupper(md5(self::SECURITY . $url . '?' . http_build_query($param))) . '&' . http_build_query(
                    $param);
        } else {
            $url = self::API_URL . $url;
            curl_setopt($this->ch, CURLOPT_URL, $url . '?' . http_build_query($param));
        }
        $output = curl_exec($this->ch);
        curl_close($this->ch);
        if ($output === false) {
            return false;
        } else {
            return json_decode($output, true);
        }
    }

    function joinPlayer($param, $url)
    {
        curl_close($this->ch);
        // unset($param['g']);
        $api = $_SERVER['HTTP_HOST'];
        if (strpos($_SERVER['HTTP_HOST'], 'www.') !== false) {
            $api = str_replace('www.', '', $api);
        }
        setcookie("g", $param['g'], time() + 3600, '/', '.' . $api);
        unset($param['g']);
        echo '<script> location.href="' . $url . '?' . http_build_query($param) . '";</script>';
    }

    function joinH5Player($param, $url)
    {
        curl_close($this->ch);
        echo '<script> location.href ="http://ismart.ib.gsoft88.net/Deposit_ProcessLogin.aspx?lang=cs&st=' . $param['g'] .
             '";</script>';
    }
}