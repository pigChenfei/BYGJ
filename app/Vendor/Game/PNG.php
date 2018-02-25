<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

use App\Vendor\Game\Imp\GameAbstract;

class PNG extends GameAbstract
{

    // API用户名
    const API_USERNAME = 'TTCCNYAPI';

    // API密码
    const API_PASSWORD = '12345678';

    // APItoken,postman生成
    const API_TOKEN = 'Basic VFRDQ05ZQVBJOjEyMzQ1Njc4';

    // 正式：http://api.gmaster8.com,测试：http://api.dynastyggroup.com
    const API_URL = 'https://api.gmaster8.com';

    // 注册
    const API_REGISTER = self::API_URL . '/register';

    // 激活玩家
    const API_ACTIVE = self::API_URL . '/PNG/player/active';

    // 获取玩家余额
    const API_BALANCE = self::API_URL . '/PNG/player/balance';

    // 存款
    const API_DEPOSIT = self::API_URL . '/PNG/credit/deposit';

    // 取款
    const API_WITHDRAWAL = self::API_URL . '/PNG/credit/withdrawal';

    // 执行游戏
    const API_OPEN = self::API_URL . '/PNG/game/open';

    // 强行关闭游戏
    const API_CLOSE = self::API_URL . '/PNG/game/close';

    // 锁定玩家
    const API_LOCK = self::API_URL . '/player/lock';

    // 解锁玩家
    const API_UNLOCK = self::API_URL . '/player/unlock';

    // 交易状态查询
    const API_CHECK_TRANSACTION = self::API_URL . '/PNG/credit/check_transaction';

    // 游戏记录查询
    const API_HISTORY = self::API_URL . '/PNG/game/history';

    // 修改玩家平台密码
    const API_CHANGE_PASSWORD = self::API_URL . '/PNG/player/change_password';

    // 获取当前平台总余额
    const API_TOTAL_BALANCE = self::API_URL . '/PNG/total_balance';

    public function pngCurl($url, $param)
    {
        $header = array();
        $header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive:timeout=5, max=100";
        $header[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3";
        $header[] = "Accept-Language:es-ES,es;q=0.8";
        $header[] = "Pragma: ";
        $header[] = "Authorization:" . self::API_TOKEN;
        $header[] = "username:" . self::API_USERNAME;
        $header[] = "password:" . self::API_PASSWORD;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param)); // post data
                                                                        // echo http_build_query($param);exit;
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            return false;
        } else {
            return json_decode($output, true);
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

    // 创建用户
    public function createMember($pre)
    {
        $playeraccount = $pre . 'Z' . $this->generate_value(6);
        $url = self::API_REGISTER;
        $password = $this->generate_value(6);
        $param = array(
            'username' => $playeraccount,
            'password' => $password
        );
        $output = $this->pngCurl($url, $param);
        if ($output && isset($output['player_name'])) {
            return array(
                'username' => $output['player_name'],
                'password' => $password
            );
        } else {
            return false;
        }
    }

    // 登录
    public function login($username, $plat = 'PNG')
    {
        $url = self::API_ACTIVE;
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $username
        );
        $output = $this->pngCurl($url, $param);
        if ($output && isset($output['status']) && $output['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    // 获取玩家余额
    public function checkBalance($username, $plat = 'PNG')
    {
        $url = self::API_BALANCE;
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $username
        );
        $output = $this->pngCurl($url, $param);
        if ($output && isset($output['balance'])) {
            return $output['balance'];
        } else {
            return false;
        }
    }

    // 转入金额
    public function transferIn($accountUserName, $money, $plat = 'PNG')
    {
        $url = self::API_DEPOSIT;
        $externalTransactionId = time() . $this->generate_value(6);
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $accountUserName,
            'amount' => $money,
            'externalTransactionId' => $externalTransactionId
        );
        $output = $this->pngCurl($url, $param);
        
        if ($output && isset($output['ending_balance'])) {
            return array(
                'balance' => $output['ending_balance'],
                'transferOrderid' => $output['transaction_id'],
                'flag' => true
            );
        } else if (! $output) {
            return array(
                'transferOrderid' => $output['transaction_id'],
                'flag' => 'unknown'
            );
        } else {
            return array(
                'transferOrderid' => $externalTransactionId,
                'flag' => false
            );
        }
    }

    // 抓单
    public function betRecord($plat = 'PNG')
    {
        $url = self::API_HISTORY;
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'fromDate' => date("Y-m-d\TH:i:s", time() - 360),
            'totoDate' => date("Y-m-d\TH:i:s", time())
        );
        var_dump($url);
        dump($param);
        $output = $this->pngCurl($url, $param);
        return $output;
    }

    // 转出金额
    public function transferOut($accountUserName, $money, $plat = 'PNG')
    {
        $url = self::API_WITHDRAWAL;
        $externalTransactionId = time() . $this->generate_value(6);
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $accountUserName,
            'amount' => $money,
            'externalTransactionId' => $externalTransactionId
        );
        $output = $this->pngCurl($url, $param);
        if ($output && isset($output['ending_balance'])) {
            return array(
                'balance' => $output['ending_balance'],
                'transferOrderid' => $output['transaction_id'],
                'flag' => true
            );
        } else if (! $output) {
            return array(
                'transferOrderid' => $output['transaction_id'],
                'flag' => 'unknown'
            );
        } else {
            return array(
                'transferOrderid' => $externalTransactionId,
                'flag' => false
            );
        }
    }

    public function playgameH5($accountUserName, $game_code, $plat = 'PNG', $clienttype = 'flash')
    {
        $url = self::API_OPEN;
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $accountUserName,
            'game_code' => $game_code,
            'lang' => 'zh-cn'
        );
        $output = $this->pngCurl($url, $param);
        \Log::info($accountUserName . ' PNG 进入游戏', $output);
        if ($output && isset($output['ticket'])) {
            $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
            $str .= '<html xmlns="http://www.w3.org/1999/xhtml">';
            $str .= '<head><title></title>';
            $str .= '<script src="https://bsicw.playngonetwork.com/Casino/js?div=pngCasinoGame&gid=' . $game_code . '&lang=zh-cn&pid=314&username=' . $output['ticket'] .
                 '&practice=0&width=1024px&height=768px" type="text/javascript"></script>';
            $str .= '</head> <body >';
            $str .= '<div id="pngCasinoGame" width="100%" height="100%"></div>';
            $str .= '</body></html>';
            echo $str;
        }
    }

    public function playmobilegameH5($accountUserName, $game_code, $plat = 'PNG', $clienttype = 'flash')
    {
        $url = self::API_OPEN;
        if ($plat != 'PNG') {
            $url = str_replace('PNG', $plat, $url);
        }
        $param = array(
            'username' => $accountUserName,
            'game_code' => $game_code,
            'lang' => 'zh-cn'
        );
        $output = $this->pngCurl($url, $param);
        \Log::info($accountUserName . ' PNG 手机版 进入游戏', $output);
        if ($output && isset($output['ticket'])) {
            $str = "https://bsicw.playngonetwork.com/casino/PlayMobile?pid=314&gid=" . $game_code . "&lang=zh_CN&ticket=" . $output['ticket'] . "&practice=0";
            echo "<script>window.location.href ='" . $str . "'</script>";
        }
    }
}