<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/11/28
 * Time: 13:16
 */
namespace App\Vendor\GameGateway\Sunbet;

use App\Models\PlayerGameAccount;
use App\Models\Def\MainGamePlat;
use App\Models\Def\Game;
use App\Models\Log\PlayerAccountLog;
use App\Http\Controllers\Web\GameController;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Observers\PlayerBetFlowObserver;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use App\Models\PlayerTransfer;
use Models\Log\GameNoneExists;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;

class Sunbet
{

    private $lastSynchronizeTime;

    public static function registerRoutes()
    {
        return [ // 'ptLoginRedirect' => 'PTGameRedirectController@index'
];
    }

    /* curl 请求方法组装 */
    private function curlRequest($url, $header_data, $post_data = '', $flag = false, $post = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if ($flag) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if ($post or $flag) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        // var_dump($error) ;
        return $output;
    }

    /* API调用的OAuth授权 测试Sunbet平台 add by tlt */
    /*
     * Oauth返回数据：
     * {"access_token":"48fZEFPe9Bs7LaK9OQKm18LSRpi3kZnFTJ6PbqDO8bfu2YYW4BQ0HrEbuUbBAsTjb","token_type":"Bearer","expires_in":3600,"scope":"playerapi"}
     */
    public function Oauth()
    {
        $url = "https://tgpaccess.com/api/oauth/token"; // 需要用这个域名测试
                                                        // 设置 Header
        $header_data = array();
        $header_data[] = 'Content-Type: application/x-www-form-urlencoded';
        
        /* 注意：这里的提交数据必须用如下格式的字符串,否则将会报错 */
        $data = "grant_type=client_credentials&scope=playerapi&client_id=ttc1122&client_secret=4LzVCYF8psVIbaog1LP2vM5SYZxAMmoto0hpkJSR2sy";
        $resData = $this->curlRequest($url, $header_data, $data, false, true);
        $resArr = json_decode($resData, true);
        if ($resArr && array_has($resArr, 'access_token')) {
            return $resArr['access_token'];
        } else {
            \WLog::info('SUNBET 平台用户登录失败', [
                'requestData' => $data
            ]);
            throw new \Exception('SUNBET 平台用户登录失败', [
                'requestData' => $data
            ]);
        }
    }

    /* ​游戏大厅端点 测试Sunbet平台 add by tlt 未测试 */
    public function gamesLobbyPoints()
    {
        $lobby_path = "";
        $authtoken = "48fZEFPe9Bs7LaK9OQKm18LSRpi3kZnFTJ6PbqDO8bfu2YYW4BQ0HrEbuUbBAsTjb";
        $url = "https://tgpaccess.com/" . $lobby_path . "?token=" . $authtoken;
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Content-Type: application/x-www-form-urlencoded';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 获取当前游戏平台id add by tlt */
    public function getGamePlatInfo($providercode = 'SB')
    {
        $main_game_plat_code = '';
        if ($providercode == 'SB') {
            $main_game_plat_code = MainGamePlat::SUNBET; // 游戏平台代码
        } else if ($providercode == 'GD') {
            $main_game_plat_code = MainGamePlat::GD; // 游戏平台代码
        } else if ($providercode == 'TGP') {
            $main_game_plat_code = MainGamePlat::TGP; // 游戏平台代码
        }
        
        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $main_game_plat_code)->first();
        return $mainGamePlatInfo;
    }

    /* 生成随机账号 add by tlt */
    public function randAccount($providercode)
    {
        $mainGamePlatInfo = $this->getGamePlatInfo($providercode);
        // 用戶名前缀信息=前缀+运营商id+'Z'+用户名
        $pre_info = $mainGamePlatInfo->account_pre . $mainGamePlatInfo->main_game_plat_id . 'Z';
        $gameC = new GameController();
        $username = $pre_info . $gameC->generate_value(6); // 账号
        return $username;
    }

    /* 获取并检查生成的随机账号 add by tlt */
    public function getRandAccount($providercode)
    {
        do {
            // 生成用户账号
            $username = $this->randAccount($providercode);
            // 检查该账号是否在我们平台上存在
            $playerGameAccountInfo = PlayerGameAccount::where('account_user_name', $username)->first();
        } while ($playerGameAccountInfo);
        return $username;
    }

    /* 检查账号是否存在，存在就获取账号 add by tlt */
    public function getCurrentPlayerAccount($providercode)
    {
        $condition['player_id'] = \WinwinAuth::memberUser()->player_id; // 当前登录后台用户的id
        $gamePlatInfo = $this->getGamePlatInfo($providercode);
        $condition['main_game_plat_id'] = $gamePlatInfo->main_game_plat_id;
        
        // 根据用户id,和游戏平台id查询当前登录用户的所在平台的账号(检查当前用户是否有sunbet的游戏账号)
        $PlayerGameAccountObj = new PlayerGameAccount();
        $userInfo = $PlayerGameAccountObj->where($condition)->first(); // 查询登录当前后台用户的mg平台游戏账号信息
        return $userInfo;
    }

    /* 玩家身份验证,相当于登录认证 测试Sunbet平台 add by tlt */
    /*
     * 该授权过程也是创建用户的过程（如果账号不存在，创建用户）
     * 返回数据格式：
     * {"authtoken":"HuL8Wq6YZjMc0PhNp1uAHvn0qf2OBnfiUabJ2lYi0eIAXSBQSEO20r05IxIuCkFB7SAFCD89B1ETfTqc7imlkd0GgAcLnp85K1bcEYCY7S6cCMs0KKpDbO39CIlNXochv","isnew":true}
     */
    public function authorize($providercode = 'SB')
    {
        $url = "https://tgpaccess.com/api/player/authorize";
        $access_token = $this->Oauth();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        $userInfo = $this->getCurrentPlayerAccount($providercode);
        if (empty($userInfo)) { // 没有游戏账号，创建游戏账号
            $username = $this->getRandAccount($providercode);
        } else { // 已有账号登录前的认证
            $username = $userInfo->account_user_name;
            if ($userInfo->is_need_repair) {
                // 判断游戏是否维护中
                throw new GameGateWayRuntimeException('当前游戏维护中');
            }
        }
        // var_dump($userInfo) ;
        
        // 设置 body
        /*
         * 下面的用户信息说明： 运营商调用玩家身份认证方法以获得一个身份验证令牌，并将更新的玩家信息传给TGP。若此指定的玩家帐
         * 户在TGP系统中不存在，就会创建一个新的账户，否则更新玩家帐户。
         */
        $body = array(
            'ipaddress' => '47.52.246.121',
            'username' => $username,
            'userid' => $username,
            'lang' => 'zh-CN',
            'cur' => 'RMB',
            'betlimitid' => 3, // 玩家的VIP等级，用来设置投注限制。
            'platformtype' => 1,
            'istestplayer' => false
        );
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($url, $header_data, $jsonBody, false, true);
        $resArr = json_decode($resData, true);
        // var_dump($resArr) ;
        if ($resArr && empty($userInfo)) {
            // 当前登录后台用户id
            $PlayerGameAccountObj = new PlayerGameAccount();
            $PlayerGameAccountObj->account_user_name = $username;
            $PlayerGameAccountObj->main_game_plat_id = $this->getGamePlatInfo($providercode)->main_game_plat_id;
            $PlayerGameAccountObj->player_id = \WinwinAuth::memberUser()->player_id;
            ;
            $PlayerGameAccountObj->extra_field = $username;
            $PlayerGameAccountObj->save();
        }
        return $resArr['authtoken'];
    }

    /* 玩家授权取消,这里相当于退出登录 测试Sunbet平台 add by tlt */
    public function deauthorize($gameAccount)
    {
        $url = "https://tgpaccess.com/api/player/deauthorize";
        $userid = $gameAccount;
        $access_token = $this->Oauth();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        $body = array(
            'userid' => $userid
        );
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($url, $header_data, $jsonBody, false, true);
        return $resData;
        // var_dump($resData);
    }

    /* ​取得（某个玩家的）余额​ ​(Wallet​ ​Balance) 测试Sunbet平台 add by tlt */
    public function getBalance($playerAccount = '')
    {
        if (empty($playerAccount)) {
            $userid = $this->getCurrentPlayerAccount()->account_user_name;
        } else {
            $userid = $playerAccount;
        }
        if (empty($userid)) { // 不存在游戏账号
            echo "游戏账号不存在";
            return;
        }
        $cur = "RMB";
        $url = "https://tgpaccess.com/api/player/balance?userid=" . $userid . "&cur=" . $cur;
        $access_token = $this->Oauth();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        $resData = json_decode($this->curlRequest($url, $header_data), true);
        // var_dump($resData['bal']);
        $balance = 0.00;
        if (! empty($resData['bal'])) {
            $balance = $resData['bal'];
            if (isset($balance)) {
                // 更新本地游戏账号余额
                $PlayerGameAccountObj = new PlayerGameAccount();
                $PlayerGameAccountObj->where('account_user_name', $userid)->update(
                    array(
                        'amount' => $balance
                    ));
            }
        }
        return $balance;
    }

    /* 取得多个玩家的余额 测试Sunbet平台 add by tlt */
    /* 返回值：返回以货币为群组的所有玩家总余额。请注意响应是CSV格式 */
    public function getMultipleBalance()
    {
        $url = "https://tgpaccess.com/api/players/balance/total";
        $access_token = "I449G5oG0qKsRkdyFEU5g5emH1aAzrWgyEWbjy3fZzU1xZfNaJj5qREvQ01GKOprZ"; // 玩家Oauth授权后的返回值access_token
                                                                                             // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 获取所有玩家的余额 测试Sunbet平台 add by tlt */
    public function getBalanceList()
    {
        $url = "https://tgpaccess.com/api/players/balance/list";
        $access_token = "I449G5oG0qKsRkdyFEU5g5emH1aAzrWgyEWbjy3fZzU1xZfNaJj5qREvQ01GKOprZ"; // 玩家Oauth授权后的返回值access_token
                                                                                             // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    // 保存转账记录
    public function saveTransferLog($money, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerAccountBalance,
        $carrierRemainQuota, $main_account_amount = 0, $player_id = '', $main_game_plat_id = '', $OpTransId = '')
    {
        \DB::beginTransaction();
        // 写日志表
        $playaccountlog = new PlayerAccountLog();
        $playaccountlog->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $playaccountlog->player_id = empty($player_id) ? \WinwinAuth::memberUser()->player_id : $player_id;
        $playaccountlog->main_game_plat_id = empty($main_game_plat_id) ? $this->getGamePlatInfo()->main_game_plat_id : $main_game_plat_id;
        $playaccountlog->amount = $money;
        $playaccountlog->fund_type = 5;
        $playaccountlog->operator_reviewer_id = NULL;
        
        $preMainAccount = empty($main_account_amount) ? \WinwinAuth::memberUser()->main_account_amount : $main_account_amount;
        
        $playaccountlog->fund_source = $msg;
        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $totalBalance . "; 游戏平台原余额：" .
             $prePlayerAccountBalance . ", 现余额：" . $afterPlayerAccountBalance;
        
        if ($playaccountlog->save() === false) {
            \DB::rollBack();
            echo "保存转账日志信息失败";
            return;
        }
        
        // 更新当前网站所属运营商的积分
        $res = \WinwinAuth::currentWebCarrier()->update(
            array(
                'remain_quota' => $carrierRemainQuota
            ));
        if ($res === false) {
            \DB::rollBack();
            echo "更新运营商总积分失败";
            return;
        }
        
        // 更新转账过程中的转账状态表
        if (PlayerTransfer::where('transid', $OpTransId)->update(array(
            'state' => 1
        )) === false) {
            \DB::rollBack();
            echo "更新转账结果状态失败";
            return;
        }
        
        if (empty($player_id)) { // 前台转账
                                 // 更新用户表主账号余额数据
            $res = \WinwinAuth::memberuser()->update(
                array(
                    'main_account_amount' => $totalBalance
                ));
            if ($res === false) {
                \DB::rollBack();
                echo "更新主账号余额失败";
                return;
            }
        } else { // 后台转账
            Player::where('player_id', $player_id)->update(
                array(
                    'main_account_amount' => $totalBalance
                ));
        }
        
        \DB::commit();
    }

    /* 电子钱包加钱​ ​(Wallet​ ​Credit) 主账号转到游戏账号 */
    public function credit($amount, $playerAccount = '', $gameplat = 'SB')
    {
        if (empty($playerAccount)) { // 前台转账
            $userid = $this->getCurrentPlayerAccount()->account_user_name;
            $totalBalance = \WinwinAuth::memberuser()->main_account_amount; // 主账号余额
            $player_id = "";
            $main_game_plat_id = "";
            $preMainAccountAmount = "";
        } else { // 后台转账
            $userid = $playerAccount;
            /* 根据游戏账号查询游戏账号详细信息 add by tlt */
            $playerAcountInfo = PlayerGameAccount::where('account_user_name', $playerAccount)->first();
            $player_id = $playerAcountInfo->player_id;
            $main_game_plat_id = $playerAcountInfo->main_game_plat_id;
            /* 根据主账号id,查询主账号转账前余额 add by tlt */
            $totalBalance = Player::where('player_id', $player_id)->first()->main_account_amount;
            $preMainAccountAmount = $totalBalance;
        }
        // 查询当前网站所属运营商的积分
        $carrierRemainQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
        
        // 主账号转到游戏账号，运营商额度减少
        $carrierRemainQuota -= $amount; // 主账号转到游戏账号运营商额度减少
        if ($carrierRemainQuota < 0) {
            echo "运营商额度不足，不能把游戏账号的钱转到主账号";
            return;
        }
        if (empty($userid)) { // 不存在游戏账号
            echo "游戏账号不存在";
            return;
        }
        
        if ($amount > $totalBalance) { // 主账号余额不足
            echo "主账号余额不足";
            return;
        }
        $url = "https://tgpaccess.com/api/wallet/credit";
        $access_token = $this->Oauth();
        $prePlayerAccountBalance = $this->getBalance($playerAccount); // 游戏账号原余额
                                                                      // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        
        $timestamp = date('Y-m-d H:i:s', time());
        // 生成订单号txid,这个每次订单需要唯一值
        $txid = str_replace(" ", "-", str_replace(':', '-', $timestamp));
        // 设置 body
        $body = array(
            'userid' => $userid,
            'amt' => $amount,
            'cur' => 'RMB',
            'txid' => $txid, // 订单号
                             // 'timestamp' => '2017-11-29T15:33:09+00:00' ,
            'timestamp' => $timestamp,
            'desc' => null
        );
        $jsonBody = json_encode($body);
        /*
         * var_dump($jsonBody) ;
         * echo "<hr/>" ;
         */
        /* 注意：这里的提交数据必须用如下格式的字符串，而不能用上面格式的json数据，否则将会报错 */
        $resData = json_decode($this->curlRequest($url, $header_data, $jsonBody, false, true), true);
        try {
            \DB::beginTransaction();
            // var_dump($resData);
            $msg = '';
            if ($gameplat == 'SB') {
                $msg = '主账户 转到 申博平台';
            } else {
                $msg = '主账户 转到 ' . $gameplat . '平台';
            }
            
            $totalBalance -= $amount; // 主账号现有余额
            $afterPlayerAccountBalance = $prePlayerAccountBalance + $amount; // 游戏账号现余额
                                                                             // 更新本地游戏账号余额
            $PlayerGameAccountObj = new PlayerGameAccount();
            $PlayerGameAccountObj->where('account_user_name', $userid)->update(
                array(
                    'amount' => $afterPlayerAccountBalance
                ));
            
            // 保存转账过程
            $OpTransId = time() . mt_rand(10000000, 99999999);
            $playerTransfer = new PlayerTransfer();
            $playerTransfer->transid = $OpTransId;
            $playerTransfer->player_id = $player_id;
            $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $playerTransfer->main_game_plats_id = $this->getGamePlatInfo($gameplat)->main_game_plat_id;
            $playerTransfer->money = $amount;
            $playerTransfer->direction = 1; // 主账号转入游戏账号
            if ($playerTransfer->save() === false) {
                \DB::rollBack();
                \WLog::info("保存转账过程状态失败");
                return;
            }
            
            if ((isset($resData) && isset($resData['bal']))) { // 请求接口成功
                $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance,
                    $afterPlayerAccountBalance, $carrierRemainQuota, $preMainAccountAmount, $player_id,
                    $main_game_plat_id, $OpTransId);
                \DB::commit();
                return true;
            }
            \DB::rollBack();
            return false;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /*
     * 电子钱包扣钱​ ​(Wallet​ ​Debit) 测试Sunbet平台 add by tlt
     * 运营商调用电子钱包扣钱方法将钱转出TGP电子钱包系统。
     *
     */
    // 游戏账号转到主账号
    public function debit($amount, $playerAccount = '', $gameplat = 'SB')
    {
        if (empty($playerAccount)) { // 前台转账
            $userid = $this->getCurrentPlayerAccount()->account_user_name;
            $totalBalance = \WinwinAuth::memberuser()->main_account_amount; // 主账号余额
            $player_id = "";
            $main_game_plat_id = "";
            $preMainAccountAmount = "";
        } else { // 后台转账
            $userid = $playerAccount;
            $this->deauthorize($playerAccount);
            /* 根据游戏账号查询游戏账号详细信息 add by tlt */
            $playerAcountInfo = PlayerGameAccount::where('account_user_name', $playerAccount)->first();
            $player_id = $playerAcountInfo->player_id;
            $main_game_plat_id = $playerAcountInfo->main_game_plat_id;
            /* 根据主账号id,查询主账号转账前余额 add by tlt */
            $totalBalance = Player::where('player_id', $player_id)->first()->main_account_amount;
            $preMainAccountAmount = $totalBalance;
        }
        if (empty($userid)) { // 不存在游戏账号
            echo "游戏账号不存在";
            return;
        }
        
        $url = "https://tgpaccess.com/api/wallet/debit";
        $access_token = $this->Oauth();
        $prePlayerAccountBalance = $this->getBalance($playerAccount); // 游戏账号原余额
        if ($amount > $prePlayerAccountBalance) {
            echo "游戏账号余额不足";
            return;
        }
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        $timestamp = date('Y-m-d H:i:s', time());
        // 生成订单号txid,这个每次订单需要唯一值
        $txid = str_replace(" ", "-", str_replace(':', '-', $timestamp));
        // 设置 body
        $body = array(
            'userid' => $userid,
            'amt' => $amount,
            'cur' => 'RMB',
            'txid' => $txid, // 订单号
            'timestamp' => $timestamp,
            'desc' => null
        );
        $jsonBody = json_encode($body);
        $resData = json_decode($this->curlRequest($url, $header_data, $jsonBody, false, true), true);
        
        try {
            \DB::beginTransaction();
            $msg = '';
            if ($gameplat == 'SB') {
                $msg = '申博平台 转到 主账户';
            } else {
                $msg = $gameplat . '平台 转到 主账户';
            }
            
            $afterPlayerAccountBalance = $prePlayerAccountBalance - $amount; // 游戏账号现余额
            
            $totalBalance += $amount; // 主账号现余额
                                      // 查询当前网站所属运营商的积分
            $carrierRemainQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
            $carrierRemainQuota += $amount; // 游戏账号转到主账号，运营商额度增加
                                            
            // 保存转账过程
            $OpTransId = time() . mt_rand(10000000, 99999999);
            $playerTransfer = new PlayerTransfer();
            $playerTransfer->transid = $OpTransId;
            $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $playerTransfer->player_id = $player_id;
            $playerTransfer->main_game_plats_id = $this->getGamePlatInfo($gameplat)->main_game_plat_id;
            $playerTransfer->money = $amount;
            $playerTransfer->direction = 2; // 游戏账号转入主账号
            if ($playerTransfer->save() === false) {
                \DB::rollBack();
                \WLog::info("保存转账过程状态失败");
                return;
            }
            if ((isset($resData) && isset($resData['bal'])) or empty($resData)) { // 请求接口成功
                                                                                  // 更新本地游戏账号余额
                $PlayerGameAccountObj = new PlayerGameAccount();
                $PlayerGameAccountObj->where('account_user_name', $userid)->update(
                    array(
                        'amount' => $afterPlayerAccountBalance
                    ));
                
                $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance,
                    $afterPlayerAccountBalance, $carrierRemainQuota, $preMainAccountAmount, $player_id,
                    $main_game_plat_id, $playerAccount, $OpTransId);
                \DB::commit();
                return true;
            }
            \DB::rollBack();
            return;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /* 转账历史​ ​(Transfer​ ​History) 测试Sunbet平台 add by tlt */
    public function transferHistory()
    {
        $startdate = urlencode("2017-11-29T09:00:00+08:00");
        $enddate = urlencode("2017-11-29T09:30:00+08:00");
        $url = "https://tgpaccess.com/api/history/transfers?startdate=" . $startdate . "&enddate=" . $enddate;
        $access_token = "I449G5oG0qKsRkdyFEU5g5emH1aAzrWgyEWbjy3fZzU1xZfNaJj5qREvQ01GKOprZ"; // 玩家Oauth授权后的返回值access_token
                                                                                             // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: text/csv';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 投注交易历史​ ​(Bet​ ​Transaction​ ​History) 测试Sunbet平台 add by tlt */
    public function betTransactionHistory()
    {
        $startdate = urlencode("2017-11-29T09:00:00+08:00");
        $enddate = urlencode("2017-11-29T09:30:00+08:00");
        $url = "https://tgpaccess.com/api/history/bettransaction?startdate=" . $startdate . "&enddate=" . $enddate;
        $access_token = "I449G5oG0qKsRkdyFEU5g5emH1aAzrWgyEWbjy3fZzU1xZfNaJj5qREvQ01GKOprZ"; // 玩家Oauth授权后的返回值access_token
                                                                                             // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: text/csv';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 将csv 格式数据转换为对应的数组格式 add by tlt */
    public function csvToArray($resData, $l = 18)
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
        // foreach ($KeyValue as $r) {
        // $res['isPaid'] = $r['roundstatus'] == "Closed" ? 1 : 0;
        // $res['gameAccount'] = $r['userid'];
        // $res['orderId'] = $r['txid'];
        // $res['gamePlat'] = $r['gameprovidercode'];
        // $res['gameCode'] = str_replace(' ', '_', $r['gamename']);
        // $res['betAmount'] = abs($r['riskamt']);
        // $res['orderDate'] = str_replace('T', '', substr($r['beton'], 0, strpos($r['beton'], '+')));
        // $res['isEffective'] = abs($r['riskamt']) == 0 ? 0 : 1;
        // $res['payOff'] = $r['winloss'] < 0 ? 0 : $r['winloss'];
        // $res['commissionable'] = abs($r['riskamt']) == 0 ? 0 : abs($r['riskamt']);
        
        // array_push($totalresult, $res);
        // }
        
        return $KeyValue;
    }

    /*
     * 游戏历史​ ​(Game​ ​History) 测试Sunbet平台 add by tlt
     * 此方法用来取得玩家的游戏历史
     */
    public function gameHistory()
    {
        $startdate = urlencode("2017-12-11T14:30:00");
        $enddate = urlencode("2017-12-11T15:00:00");
        $url = "https://tgpaccess.com/api/history/bettransaction?startdate=" . $startdate . "&enddate=" . $enddate;
        $access_token = $this->Oauth(); // 玩家Oauth授权后的返回值access_token
                                        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: text/csv';
        $resData = $this->curlRequest($url, $header_data);
        $csvToArray = $this->csvToArray($resData, 19); // 数组数据
    }

    /*
     * 投注历史​ ​(Bet​ ​History) 测试Sunbet平台 add by tlt
     * 此方法用来取得玩家的游戏历史
     */
    public function betHistory($startdate = '', $enddate = '')
    {
        // $startdate = "2017-12-26T14:30:00";
        // $enddate = "2017-12-26T15:20:00";
        echo "betHistory--->startdate=$startdate ; enddate=$enddate \n";
        $url = "https://tgpaccess.com/api/history/bets?startdate=" . $startdate . "&enddate=" . $enddate;
        $access_token = $this->Oauth(); // 玩家Oauth授权后的返回值access_token
                                        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: bearer ' . $access_token;
        $resData = $this->curlRequest($url, $header_data);
        /*
         * echo "betHistory return data:" ;
         * var_dump($resData) ;
         */
        \WLog::info("========  sunbet 原始数据 csv  ========", [
            'message' => $resData
        ]);
        $csvToArray = $this->csvToArray($resData); // 数组数据
        return $csvToArray;
    }

    /* ​来自游戏供应商的游戏历史 测试Sunbet平台 add by tlt */
    public function providersHistory()
    {
        $userid = "taozi123";
        $gpcode = "SB";
        $roundid = "ugs-20807857"; // roundid在投注历史（betHistory接口）中有返回
        $url = "https://tgpaccess.com/api/history/providers/" . $gpcode . "/rounds/" . $roundid . "/users/" . $userid;
        $access_token = "I449G5oG0qKsRkdyFEU5g5emH1aAzrWgyEWbjy3fZzU1xZfNaJj5qREvQ01GKOprZ"; // 玩家Oauth授权后的返回值access_token
                                                                                             // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'Content-Type: text/csv';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 9.​ ​直接启动游戏 start */
    /* 游戏列表API 游戏列表API用于获取特定品牌的游戏列表。 */
    public function getGamesList()
    {
        // $lang = "en-US" ;
        $lang = "zh-cn";
        $platformtype = 0;
        $iconres = "343x200";
        // $authtoken = "7VN62Ob1nEKjokz9kEoVZDS3RWWj0tbbTMafYOUBOBtVILNEniPp0uw1AmyFTVO464iFNYAHdIBOYF9GDlMPRhn6o3EN8xpiYcd4vXM2AxlbEXHQfokiMwCd4u3rOdWObp" ;
        $authtoken = $this->authorize();
        $url = "https://tgpaccess.com/api/games?lang=" . $lang . "&platformtype=" . $platformtype . "&iconres=" .
             $iconres . "&authtoken=" . $authtoken;
        // $access_token = "SrYr0lMwLEq5S7vmdOz1CxayGyKWEXQ2UuqJkk4PuJ6EkstM3yzAEGQGCewf6tbsG"; //玩家Oauth授权后的返回值access_token
        $access_token = $this->Oauth();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Content-Type: application/json';
        $header_data[] = 'Accept: application/json';
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $resData = $this->curlRequest($url, $header_data);
        $resArr = json_decode($resData, true);
        $gameList = $resArr['games'];
        $i = 0;
        foreach ($gameList as $value) {
            // var_dump($value) ;
            if ($value['providercode'] == 'TGP') { // 供应商代码写入数据库
                $gameObj = new Game();
                $gameObj->game_plat_id = 10;
                $gameObj->main_game_plat_id = 5;
                $gameObj->game_name = $value['name'];
                $gameObj->game_type = $value['code'];
                $gameObj->save();
                unset($gameObj);
                $i ++;
            }
        }
        echo $i;
        /*
         * echo "<pre>" ;
         * var_dump($gameList) ;
         * echo "<pre/>" ;
         */
        return $gameList;
    }

    /* 进入游戏启动页 */
    /*
     * 游戏启动页是让运营商启动游戏使用。运营商可以为每个游戏创建游戏启动网址，并使用该网址来启动游
     * 戏。
     */
    /* 此处需要注意：这个域名tgpasia.com，与前面的不同 */
    public function gameLauncher($providercode, $code)
    {
        // $authtoken = "7VN62Ob1nEKjokz9kEoVZDS3RWWj0tbbTMafYOUBOBtVILNEniPp0uw1AmyFTVO464iFNYAHdIBOYF9GDlMPRhn6o3EN8xpiYcd4vXM2AxlbEXHQfokiMwCd4u3rOdWObp" ;//玩家令牌
        $authtoken = $this->authorize($providercode);
        // $gpcode = "TGP" ; //供应商平台
        // $gcode = "Arcade_Bomb" ; //游戏代码
        // $gameList = $this ->getGamesList() ; //获取游戏列表
        // $gpcode = $gameList[0]['providercode'] ; //供应商平台
        // $gcode = $gameList[0]['code'] ; //游戏代码
        $platform = 0; // 供应商平台
        $url = "https://tgpasia.com/gamelauncher?gpcode=" . $providercode . "&gcode=" . $code . "&platform=" . $platform .
             "&token=" . $authtoken;
        // 这里根据url地址直接跳转到游戏界面
        Header("Location:$url");
    }

    public function gameH5Launcher($providercode, $code)
    {
        $authtoken = $this->authorize($providercode);
        $platform = 0;
        $url = '';
        if ($providercode == 'SB') {
            $url = "https://tgpasia.com/SBmlobby?gpcode=" . $providercode . "&gcode=" . $code . "&platform=" . $platform .
                 "&token=" . $authtoken;
        } else if ($providercode == 'GD') {
            $url = "https://tgpasia.com/GDmlobby?gpcode=" . $providercode . "&gcode=" . $code . "&platform=" . $platform .
                 "&token=" . $authtoken;
        } else if ($providercode == 'TGP') {
            // $url = "https://tgpasia.com/RTmlobby?gpcode=" . $providercode . "&gcode=" . $code . "&platform=" . $platform ."&token=" . $authtoken;
            $url = "https://tgpasia.com/gamelauncher?gpcode=" . $providercode . "&gcode=" . $code . "&platform=" .
                 $platform . "&token=" . $authtoken;
        }
        // 这里根据url地址直接跳转到游戏界面
        Header("Location:$url");
    }

    /* 9.​ ​直接启动游戏 end */
    
    /**
     * ** 获取最后一次同步时间 add by tlt ****
     */
    public function lastSynchronizedGameFlowTimeStamp()
    {
        // 获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if (! $this->lastSynchronizeTime && \Storage::exists('SB_synchronizedTimestamp')) {
            $this->lastSynchronizeTime = \Storage::get('SB_synchronizedTimestamp');
        }
        // 如果上次的时间在28(接近30)分钟之前, 那么我们将时间强制减到20分钟 或者 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
        if (! $this->lastSynchronizeTime || $this->lastSynchronizeTime <= date('Y-m-d H:i:s', time() - 28 * 60)) {
            // 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
            $this->lastSynchronizeTime = date('Y-m-d H:i:s', time() - 20 * 60);
        }
        return $this->lastSynchronizeTime;
    }

    /**
     * 获取游戏流水记录
     *
     * @param GameGatewaySearchCondition $condition
     * @param PlayerGameAccount|null $gameAccount
     * @return GameGatewayBetFlowRecord[]
     * @throws \Exception
     */
    public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null)
    {
        $start_time = str_replace(' ', 'T', $condition->start_date);
        $end_time = str_replace(' ', 'T', $condition->end_date);
        /*
         * $start_time = str_replace(' ','T',date("Y-m-d H:i:s",time()-3600*1000)) ; //当前时间1000小时前的时间
         * $end_time = str_replace(' ','T',date("Y-m-d H:i:s",time())) ; //当前时间
         */
        try {
            $result = $this->betHistory($start_time, $end_time); // 获取下注记录
            \WLog::info('====>sunbet 请求数据开始',
                [
                    'msessage' => $result,
                    'start' => $start_time,
                    'endtime' => $end_time
                ]);
            if ($result) {
                // 过滤没有投注金额的投注记录
                $result = array_filter($result,
                    function ($element) {
                        return $element['riskamt'] || $element['winamt'];
                    });
                return array_map(
                    function ($element) {
                        $flowRecord = new GameGatewayBetFlowRecord();
                        $flowRecord->playerName = $element['username'];
                        $flowRecord->gameType = $element['gamename'];
                        $flowRecord->gameName = $element['gamename'];
                        $flowRecord->gameCode = str_replace(" ", "_", $element['gamename']);
                        $flowRecord->bet = abs($element['riskamt']);
                        $flowRecord->win = $element['winamt'];
                        $flowRecord->progressiveBet = abs($element['riskamt']);
                        $flowRecord->progressiveWin = $element['winamt'];
                        $flowRecord->balance = $element['postbal'];
                        $flowRecord->currentBet = abs($element['riskamt']);
                        $flowRecord->date = str_replace('T', '',
                            substr($element['timestamp'], 0, strpos($element['timestamp'], '+')));
                        $flowRecord->code = $element['txid'];
                        // 是否投注流水有效
                        // 如果是老虎机, 那么都是有效流水
                        // 如果是真人游戏, 判断是投的庄家还是闲家, 如果庄家和闲家投注一样, 那么也是无效投注流水, 如果是和局那么是无效投注流水, 如果庄家和闲家投注都投了, 选取他们的差额作为有效投注流水.
                        $flowRecord->betInfo = $element['bettype'];
                        // 真人游戏正则;
                        $flowRecord->isBetAvailable = true;
                        $flowRecord->availableBet = $flowRecord->bet;
                        if ($element['validbet'] <= 0) { // 有效投注。此值为正数。
                            $flowRecord->isBetAvailable = false;
                            $flowRecord->availableBet = 0;
                        } else {
                            $flowRecord->isBetAvailable = true;
                            $flowRecord->availableBet = $element['validbet'];
                        }
                        return $flowRecord;
                    }, $result);
            }
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新同步时间到磁盘
     *
     * @param $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time)
    {
        \Storage::put('SB_synchronizedTimestamp', $time);
    }

    /**
     *
     * @param GameGatewayBetFlowRecord[] $betFlowRecords
     */
    // 数据同步保存到硬盘上
    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition,
        PlayerGameAccount $gameAccount = null)
    {
        try {
            $betFlowRecords = $this->fetchGameFlowResult($condition, $gameAccount);
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
        $lastSynchronizeTime = null;
        foreach ($betFlowRecords as $record) {
            try {
                $playerBetFlow = new PlayerBetFlowLog();
                $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record->playerName);
                
                if (! $playerGameAccount) {
                    throw new GameGateWayRuntimeException('该会员无法找到相应的游戏账户:' . $record->playerName);
                }
                $playerGameAccount->amount = $record->balance;
                $game = Game::getCachedGameByGameCode($record->gameCode);
                /* 这里需要先录入游戏到数据库中然后在查询 */
                if (! $game) {
                    $noneExists = array(
                        'game_plat' => 'SUNBET',
                        'game_flow_code' => $record->code,
                        'game_code' => $record->gameCode,
                        'game_name' => $record->gameName,
                        'message' => '无法找到游戏:' . $record->gameName
                    );
                    
                    try {
                        $exists = \DB::table('log_game_none_exixts')->where(
                            [
                                'game_plat' => $noneExists->game_plat,
                                'game_code' => $record->gameCode
                            ])->first();
                        if (! $exists) {
                            \DB::insert(
                                "insert into `log_game_none_exixts` (`game_plat`,`game_flow_code`,`game_code`,`game_name`,`message`,`created_at`,`updated_at`) value (?,?,?,?,?,now(),now())",
                                array_values($noneExists));
                        }
                    } catch (\PDOException $e) {
                        throw $e;
                    }
                    throw new GameGateWayRuntimeException('无法找到游戏:' . $record->gameCode);
                }
                
                $playerBetFlow->player_id = $playerGameAccount->player_id;
                // 查询到会员id即可查询到carrier_id;
                $playerBetFlow->carrier_id = $playerGameAccount->player->carrier_id;
                
                $playerBetFlow->game_id = $game->game_id;
                $playerBetFlow->game_plat_id = $game->game_plat_id; // 这里占时先写一个固定的值
                $playerBetFlow->game_flow_code = $record->code;
                $playerBetFlow->bet_amount = $record->bet;
                $playerBetFlow->company_payout_amount = $record->win;
                $playerBetFlow->company_win_amount = $record->bet - $record->win; // 净投注额
                $playerBetFlow->bet_flow_available = $record->isBetAvailable;
                $playerBetFlow->available_bet_amount = $record->availableBet;
                $playerBetFlow->player_or_banker = 0;
                $playerBetFlow->created_at = date('Y-m-d H:i:s', strtotime($record->date));
                $playerBetFlow->game_status = PlayerBetFlowLog::GAME_STATUS_FINISHED;
                $playerBetFlow->progressive_bet = $record->progressiveBet;
                $playerBetFlow->progressive_win = $record->progressiveWin;
                $playerBetFlow->bet_info = $record->betInfo;
                
                if (! $lastSynchronizeTime || $lastSynchronizeTime < $record->date) {
                    $lastSynchronizeTime = $record->date;
                }
                $conditionData = json_decode(json_encode($playerBetFlow), true);
                \WLog::info('====> sunbet入库开始', $conditionData);
                // $findData = \DB::table('log_player_bet_flow')->where(
                $findData = PlayerBetFlowLog::where(
                    [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'game_id' => $playerBetFlow->game_id,
                        'game_plat_id' => $playerBetFlow->game_plat_id,
                        'player_id' => $playerBetFlow->player_id,
                        'carrier_id' => $playerBetFlow->carrier_id
                    ])->first();
                if (empty($findData)) { // 数据不存在，保存数据
                    \WLog::info('====> sunbet 入库');
                    \DB::transaction(
                        function () use ($playerGameAccount, $playerBetFlow) {
                            $playerGameAccount->save();
                            $playerBetFlow->save();
                        });
                    // \DB::insert(
                    // "insert into `log_player_bet_flow` (" . implode(",", array_keys($conditionData)) . ",`updated_at`)
                    // value (?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                    // array_values($conditionData));
                } else {
                    $findData->fill($conditionData);
                    $findData->save();
                }
                
                \WLog::info('====>会员投注记录同步成功:',
                    [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'date' => $record->date
                    ]);
            } catch (\Exception $e) {
                \WLog::error('====>会员投注记录同步失败:', [
                    'message' => $e->getMessage()
                ]);
                throw $e;
            }
        }
        $pos = strpos($lastSynchronizeTime, '.');
        if ($pos) { // 时间字符串中包含秒数
            $lastSynchronizeTime = substr($lastSynchronizeTime, 0, $pos);
        }
        $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $lastSynchronizeTime)->addSeconds(1)->toDateTimeString();
        return $betFlowRecords;
    }
}