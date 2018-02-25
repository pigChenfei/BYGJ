<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */
namespace App\Vendor\Game;

use App\Vendor\Game\Imp\GameAbstract;
use App\Models\PlayerTransfer;
use App\Models\PlayerGameAccount;

class BBin extends GameAbstract
{

    const URL = 'http://linkapi.bbinauth.net/app/WebService/JSON/display.php/';

    const URL1 = 'http://888.bbinauth.net/app/WebService/JSON/display.php/';

    const CreateMemberKeyB = 'fA8eIS090';

    const LoginKeyB = 'BaD6088';

    const Login2KeyB = 'BaD6088';

    const LogoutKeyB = '92E8E6a';

    const CheckUsrBalanceKeyB = '0c4eI930d';

    const TransferKeyB = 'Q38bF5aCq2';

    const CheckTransferKeyB = 'cV4e56A83';

    const TransferRecordKeyB = 'cV4e56A83';

    const PlayGameKeyB = '4c8F93Hf9';

    const PlayGameByH5KeyB = '4c8F93Hf9';

    const ForwardGameH5By30KeyB = '4c8F93Hf9';

    const ForwardGameH5By38KeyB = '4c8F93Hf9';

    const BetRecordKeyB = '1E8PaDLk5c';

    const BetRecordByModifiedDate3KeyB = '1E8PaDLk5c';

    const GetJPHistoryKeyB = '1E8PaDLk5c';

    const Website = 'ttc1122';

    const Uppername = 'dttc1122';

    const Gmt = - 14400;

    // 创建用户
    const API_CreateMember = self::URL . 'CreateMember';

    // 会员登出
    const API_Logout = self::URL . 'Logout';

    // 查询会员余额
    const API_CheckUsrBalance = self::URL . 'CheckUsrBalance';

    // 转帐
    const API_Transfer = self::URL . 'Transfer';

    // 查询会员转帐是否成功
    const API_CheckTransfer = self::URL . 'CheckTransfer';

    // 查询会员转帐记录
    const API_TransferRecord = self::URL . 'TransferRecord';

    // 下注纪录
    const API_BetRecord = self::URL . 'BetRecord';

    // 下注纪录(查开奖时间)
    const API_BetRecordByModifiedDate3 = self::URL . 'BetRecordByModifiedDate3';

    // JP开奖历史记录,不分体系
    const API_GetJPHistory = self::URL . 'GetJPHistory';

    // 登录大厅
    const API_Login = self::URL1 . 'Login';

    // 会员登录
    const API_Login2 = self::URL1 . 'Login2';

    // 直接导入flash
    const API_PlayGame = self::URL1 . 'PlayGame';

    // 直接导入真人H5游戏
    const API_PlayGameByH5 = self::URL1 . 'PlayGameByH5';

    // 直接导入H5电子游戏
    const API_ForwardGameH5By5 = self::URL1 . 'ForwardGameH5By5';

    // 捕鱼达人
    const API_ForwardGameH5By30 = self::URL1 . 'ForwardGameH5By30';

    // 直接导入H5电子游戏
    const API_ForwardGameH5By38 = self::URL1 . 'ForwardGameH5By38';

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
     * @ 创建用户
     * @pre 帐号名前辍
     */
    public function createMember($pre)
    {
        $username = $pre . 'Z' . $this->generate_value(6);
        $param = array(
            'username' => $username,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $username . self::CreateMemberKeyB . $datestr)));
        $param['key'] = $this->createRand(5) . $md5str . $this->createRand(2);
        $result = $this->bbinCurl(BBin::API_CreateMember, $param);
        if (! $result || ! $result['result']) {
            return false;
        } else {
            return $username;
        }
    }

    /**
     * @type=12 BB彩票,type=1 BB体育,type=5 BB电子,type=3 BB真人
     * @minute 多少分钟抓一次
     * @longminute 抓取多少分钟的数据
     * @isdate 是否只精确到天
     */
    public function betRecord($type, $longminute, $isdate = false)
    {
        $time = time() - 43200;
        $totalresult = array();
        $param = array();
        $gametype = '';
        $subgamekind = '';
        if ($type == 12) {
            $param = array(
                'uppername' => self::Uppername,
                'website' => self::Website,
                'page' => '1',
                'gamekind' => $type
            );
            $gametype = array(
                'LT',
                'OTHER'
            );
        } else if ($type == 1) {
            $param = array(
                'uppername' => BBin::Uppername,
                'website' => BBin::Website,
                'page' => '1',
                'gamekind' => $type
            );
        } else if ($type == 5) {
            $param = array(
                'uppername' => BBin::Uppername,
                'website' => BBin::Website,
                'page' => '1',
                'gamekind' => $type
            );
            $subgamekind = array(
                1,
                2,
                3,
                5
            );
        } else if ($type == 3) {
            $param = array(
                'uppername' => BBin::Uppername,
                'website' => BBin::Website,
                'page' => '1',
                'gamekind' => $type
            );
        }
        if (! empty($gametype)) {
            foreach ($gametype as $v1) {
                $param['gametype'] = $v1;
                $param = $this->timeInterval($param, $time, $longminute, $isdate);
                $result = $this->bbinMorePage($param, $type);
                if (count($result)) {
                    array_push($totalresult, $result);
                }
            }
        } else if (! empty($subgamekind)) {
            foreach ($subgamekind as $v1) {
                $param['subgamekind'] = $v1;
                $param = $this->timeInterval($param, $time, $longminute, $isdate);
                $result = $this->bbinMorePage($param, $type);
                if (count($result)) {
                    array_push($totalresult, $result);
                }
            }
        } else {
            $param = $this->timeInterval($param, $time, $longminute, $isdate);
            $totalresult = $this->bbinMorePage($param, $type);
        }
        
        return $totalresult;
    }

    /**
     *
     * @param
     *            参数
     *            @抓取多页数据
     */
    private function bbinMorePage($param, $type)
    {
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $totalresult = array();
        $bool = true;
        do {
            $md5str = strtolower(md5(utf8_encode(self::Website . self::BetRecordKeyB . $datestr)));
            $param['key'] = $this->createRand(1) . $md5str . $this->createRand(7);
            $result = $this->bbinCurl(BBin::API_BetRecord, $param);
            $res = array();
            
            // BB彩票
            if ($type == 12) {
                if ($result && $result['result'] && count($result['data'])) {
                    foreach ($result['data'] as $r) {
                        $res['gameAccount'] = $r['UserName'];
                        $res['orderId'] = $r['WagersID'];
                        $res['gameType'] = $r['GameType'];
                        $res['betAmount'] = $r['BetAmount'];
                        $res['orderDate'] = $r['WagersDate'];
                        $res['isEffective'] = $r['Result'] == 'N2' ? 0 : 1;
                        $res['payOff'] = $r['Payoff'] < 0 ? 0 : $r['Payoff'];
                        $res['isPaid'] = $r['IsPaid'] == 'Y' ? 1 : 0;
                        $res['commissionable'] = $r['Result'] == 'N2' ? 0 : $res['betAmount'];
                        array_push($totalresult, $res);
                    }
                    $param['page'] = $param['page'] + 1;
                }
                if (! $result || ! $result['result'] || $result['pagination']['TotalPage'] == 1 || $result['pagination']['TotalPage'] == 0 || ($result['pagination']['Page'] == $result['pagination']['TotalPage'])) {
                    $bool = false;
                }
            } // BB体育
else if ($type == 1) {
                if ($result && $result['result'] && count($result['data'])) {
                    foreach ($result['data'] as $r) {
                        $res['gameAccount'] = $r['UserName'];
                        $res['orderId'] = $r['WagersID'];
                        $res['gameType'] = $r['GameType'];
                        $res['orderDate'] = $r['WagersDate'];
                        $res['betAmount'] = $r['BetAmount'];
                        $res['payOff'] = $r['Payoff'] < 0 ? 0 : $r['Payoff'];
                        $res['commissionable'] = $r['Commissionable'];
                        $res['isEffective'] = ($r['Result'] == 'C' || $r['Result'] == 'F') ? 0 : 1;
                        
                        if ($r['Result'] == 'X' || $r['Result'] == 'S' || $r['Result'] == 'D' || $r['Result'] == 'N') {
                            $res['isPaid'] = 0;
                        } else {
                            $res['isPaid'] = 1;
                        }
                        
                        array_push($totalresult, $res);
                    }
                    $param['page'] = $param['page'] + 1;
                }
                if (! $result || ! $result['result'] || $result['pagination']['TotalPage'] == 1 || $result['pagination']['TotalPage'] == 0 || ($result['pagination']['Page'] == $result['pagination']['TotalPage'])) {
                    $bool = false;
                }
            } // BB电子
else if ($type == 5) {
                if ($result && $result['result'] && count($result['data'])) {
                    foreach ($result['data'] as $r) {
                        $res['gameAccount'] = $r['UserName'];
                        $res['orderId'] = $r['WagersID'];
                        $res['gameType'] = $r['GameType'];
                        $res['orderDate'] = $r['WagersDate'];
                        $res['betAmount'] = $r['BetAmount'];
                        $res['payOff'] = $r['Payoff'] < 0 ? 0 : $r['Payoff'];
                        $res['commissionable'] = $r['Commissionable'];
                        $res['isEffective'] = $r['Result'] == '-1' ? 0 : 1;
                        
                        if ($r['Result'] == '1' || $r['Result'] == '200') {
                            $res['isPaid'] = 1;
                        } else {
                            $res['isPaid'] = 0;
                        }
                        
                        array_push($totalresult, $res);
                    }
                    $param['page'] = $param['page'] + 1;
                }
                
                if (! $result || ! $result['result'] || $result['pagination']['TotalPage'] == 1 || $result['pagination']['TotalPage'] == 0 || ($result['pagination']['Page'] == $result['pagination']['TotalPage'])) {
                    $bool = false;
                }
            } // BB真人
else if ($type == 3) {
                if ($result && $result['result'] && count($result['data'])) {
                    foreach ($result['data'] as $r) {
                        $res['gameAccount'] = $r['UserName'];
                        $res['orderId'] = $r['WagersID'];
                        $res['gameType'] = $r['GameType'];
                        $res['orderDate'] = $r['WagersDate'];
                        $res['betAmount'] = $r['BetAmount'];
                        $res['payOff'] = $r['Payoff'] < 0 ? 0 : $r['Payoff'];
                        $res['commissionable'] = $r['Commissionable'];
                        $res['isEffective'] = $r['ResultType'] == '-1' ? 0 : 1;
                        
                        if ($r['ResultType'] == '0') {
                            $res['isPaid'] = 0;
                        } else {
                            $res['isPaid'] = 1;
                        }
                        
                        array_push($totalresult, $res);
                    }
                    $param['page'] = $param['page'] + 1;
                }
                
                if (! $result || ! $result['result'] || $result['pagination']['TotalPage'] == 1 || $result['pagination']['TotalPage'] == 0 || ($result['pagination']['Page'] == $result['pagination']['TotalPage'])) {
                    $bool = false;
                }
            }
        } while ($bool);
        return $totalresult;
    }

    /*
     * @isdate 是否只需精确到天
     * @longminute 抓取多长时间的数据
     * @time 当前时间
     */
    private function timeInterval($param, $time, $longminute, $isdate)
    {
        $longminutetime = $longminute * 60;
        
        // 当天凌晨的时间戳
        $dawn = strtotime(date('Y-m-d', $time));
        
        if ($isdate) {
            // 查询当天时间
            $param['rounddate'] = date("Y/m/d", $time);
        } else {
            if ($time - $longminutetime >= $dawn) {
                // 查询时长不跨天
                $param['rounddate'] = date("Y/m/d", $time);
                $param['starttime'] = date("H:i:s", $time - $longminutetime);
                $param['endtime'] = date("H:i:s", $time);
            } else {
                // 查询时长跨天
                $param['rounddate'] = date("Y/m/d", $time);
                $param['starttime'] = date("H:i:s", $dawn);
                $param['endtime'] = date("H:i:s", $time);
            }
        }
        return $param;
    }

    /**
     * @accountUserName 平台游戏帐号
     * @登录大厅
     */
    public function loginhall($accountUserName, $type = null)
    {
        $param = array(
            'username' => $accountUserName,
            'uppername' => self::Uppername,
            'website' => self::Website
        );
        if (! is_null($type))
            $param['page_site'] = $type;
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $accountUserName . self::LoginKeyB . $datestr)));
        $param['key'] = $this->createRand(8) . $md5str . $this->createRand(1);
        
        $str = '';
        foreach ($param as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        return '<script>window.location = "' . BBin::API_Login . '?' . $str . '";</script>';
    }

    /**
     * @accountUserName 平台游戏帐号
     * @检查订单返回1=成功,-1=失败，false查询出错
     */
    public function checkTransfer(PlayerTransfer $playTransfer, PlayerGameAccount $account)
    {
        $param = array(
            'transid' => $playTransfer->transid,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . self::CheckTransferKeyB . $datestr)));
        $param['key'] = $this->createRand(5) . $md5str . $this->createRand(4);
        $result = $this->bbinCurl(BBin::API_CheckTransfer, $param);
        if (! $result || ! $result['result']) {
            return 0;
        } else {
            return $result['data']['Status'] == 0 ? 1 : - 1;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @踢用户下线
     */
    public function kick($accountUserName)
    {
        $param = array(
            'username' => $accountUserName,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $accountUserName . self::LogoutKeyB . $datestr)));
        $param['key'] = $this->createRand(4) . $md5str . $this->createRand(6);
        
        $result = $this->bbinCurl(BBin::API_Logout, $param);
        if (! $result || ! $result['result']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @金额转出游戏平台
     */
    public function transferOut($accountUserName, $money)
    {
        $remitno = time() . mt_rand(10000000, 99999999);
        $param = array(
            'username' => $accountUserName,
            'remit' => intval($money),
            'remitno' => $remitno,
            'action' => 'OUT',
            'uppername' => self::Uppername,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . $remitno . self::TransferKeyB . $datestr)));
        $param['key'] = $this->createRand(2) . $md5str . $this->createRand(7);
        $result = $this->bbinCurl(BBin::API_Transfer, $param);
        
        if (isset($result['result']) && $result['result'] == 'True') {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
            );
        } else if (! $result) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
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
        $remitno = time() . mt_rand(10000000, 99999999);
        $param = array(
            'username' => $accountUserName,
            'remit' => intval($money),
            'remitno' => $remitno,
            'action' => 'IN',
            'uppername' => self::Uppername,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . $remitno . self::TransferKeyB . $datestr)));
        $param['key'] = $this->createRand(2) . $md5str . $this->createRand(7);
        $result = $this->bbinCurl(BBin::API_Transfer, $param);
        
        if (isset($result['result']) && $result['result'] == 'True') {
            return array(
                'result' => true,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
            );
        } else if (! $result) {
            return array(
                'result' => 'unknown',
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
            );
        } else {
            return array(
                'result' => false,
                'accountUserName' => $accountUserName,
                'money' => $money,
                'ordernumber' => $remitno
            );
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
            'username' => $accountUserName,
            'uppername' => self::Uppername,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::CheckUsrBalanceKeyB . $datestr)));
        $param['key'] = $this->createRand(9) . $md5str . $this->createRand(6);
        
        $result = $this->bbinCurl(self::API_CheckUsrBalance, $param);
        
        if (($result && ! $result['result']) || ! $result) {
            return false;
        } else {
            return $result['data'][0]['TotalBalance'];
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @game 游戏相关信息
     *
     * @return 登录失败返回false,
     */
    private function login($accountUserName)
    {
        // login2登录
        $param = array(
            'username' => $accountUserName,
            'uppername' => self::Uppername,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::Login2KeyB . $datestr)));
        $param['key'] = $this->createRand(8) . $md5str . $this->createRand(1);
        
        $result = $this->bbinCurl(self::API_Login2, $param);
        
        if (($result && ! $result['result']) || ! $result) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @accountUserName 平台游戏帐号
     * @isfishing 0=电子游艺，1=捕鱼达人，2=捕鱼大师
     *
     * @return 登录失败返回false,否则跳转
     */
    public function playgameH5($accountUserName, $gametype, $gamekind, $isfishing = 0)
    {
        $flag = $this->login($accountUserName);
        
        // 登录失败
        if (! $flag) {
            return false;
        }
        $param = array(
            'username' => $accountUserName,
            'gametype' => $gamekind,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $str = '';
        $url = '';
        if ($isfishing == 1) {
            $this->forwardGameH5By30($accountUserName);
        } else if ($isfishing == 2) {
            $this->forwardGameH5By38($accountUserName);
        } else {
            // 电子游H5登录
            $param['gamekind'] = $gametype;
            $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::PlayGameByH5KeyB . $datestr)));
            $param['key'] = $this->createRand(7) . $md5str . $this->createRand(1);
            $url = self::API_PlayGameByH5;
            foreach ($param as $k => $v) {
                $str .= '&' . $k . '=' . $v;
            }
            echo '<script>window.location = "' . $url . '?' . $str . '";</script>';
        }
    }

    /**
     * @accountUserName 捕鱼达人
     * @game 游戏相关信息,包括game_type,game_mcategory,game_kind,sub_game_kind;
     *
     * @return 登录失败返回false,否则跳转
     */
    public function forwardGameH5By30($accountUserName)
    {
        $flag = $this->login($accountUserName);
        
        // 登录失败
        if (! $flag) {
            return false;
        }
        $param = array(
            'username' => $accountUserName,
            'uppername' => self::Uppername,
            'gametype' => 30599,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::ForwardGameH5By30KeyB . $datestr)));
        $param['key'] = $this->createRand(7) . $md5str . $this->createRand(1);
        $url = self::API_ForwardGameH5By30;
        $str = '';
        foreach ($param as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        echo '<script>window.location = "' . $url . '?' . $str . '";</script>';
    }

    /**
     * @accountUserName 捕鱼大师
     * @game 游戏相关信息;
     *
     * @return 登录失败返回false,否则跳转
     */
    public function forwardGameH5By38($accountUserName)
    {
        $flag = $this->login($accountUserName);
        
        // 登录失败
        if (! $flag) {
            return false;
        }
        $param = array(
            'username' => $accountUserName,
            'uppername' => self::Uppername,
            'gametype' => 38001,
            'website' => self::Website
        );
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::ForwardGameH5By38KeyB . $datestr)));
        $param['key'] = $this->createRand(7) . $md5str . $this->createRand(1);
        $url = self::API_ForwardGameH5By38;
        $str = '';
        foreach ($param as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        echo '<script>window.location = "' . $url . '?' . $str . '";</script>';
    }

    /**
     * bbincurl抓取
     */
    private function bbinCurl($url, $param)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);
        if (false === $output) {
            return false;
        } else {
            return json_decode($output, true);
        }
    }

    /**
     * 产生随机数
     */
    private function createRand($number)
    {
        switch ($number) {
            case 1:
                $random = mt_rand(1, 9);
                break;
            case 2:
                $random = mt_rand(10, 99);
                break;
            case 3:
                $random = mt_rand(100, 999);
                break;
            case 4:
                $random = mt_rand(1000, 9999);
                break;
            case 5:
                $random = mt_rand(10000, 99999);
                break;
            case 6:
                $random = mt_rand(100000, 999999);
                break;
            case 7:
                $random = mt_rand(1000000, 9999999);
                break;
            case 8:
                $random = mt_rand(10000000, 99999999);
                break;
            case 9:
                $random = mt_rand(100000000, 999999999);
                break;
            default:
                break;
        }
        return $random;
    }
}