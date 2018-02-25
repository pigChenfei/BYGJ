<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/11/25
 * Time: 14:50
 */
namespace App\Vendor\GameGateway\MGEUR_API;

use App\Models\PlayerGameAccount;
use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerAccountLog;
use App\Http\Controllers\Web\GameController;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Def\Game;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use App\Models\PlayerTransfer;

class MGEUR_API
{

    private $mainGamePlat;

    private $lastSynchronizeTime;

    public static function registerRoutes()
    {
        return [ // 'ptLoginRedirect' => 'PTGameRedirectController@index'
];
    }

    /* curl 请求方法组装 */
    private function curlRequest($url, $header_data, $post_data = '', $flag = false, $post = false)
    { // $operateMethod= CURLOPT_POST,$value=1)
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
        return $output;
    }

    /* 登录认证，获取登录token(下面用的access_token) 测试接口 */
    public function login()
    {
        // echo 111; return ;
        $url = 'https://api.adminserv88.com/oauth/token';
        $authUsername = "";
        $authPasswod = "";
        $apiUsername = "TTCCNYAPI";
        $apiPassword = "ss#maEHF#hTR+=7S2yRtYD6&";
        // $auth64 = base64_encode($authUsername . ':' . $authPasswod);
        $auth64 = "R2FtaW5nTWFzdGVyMUNOWV9hdXRoOjlGSE9SUWRHVFp3cURYRkBeaVpeS1JNZ1U="; // 这里用固定的值
                                                                                      
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Basic ' . $auth64;
        $header_data[] = 'application/x-www-form-urlencoded;charset=utf-8';
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $post_data = array(
            'grant_type' => 'password',
            'username' => $apiUsername,
            'password' => $apiPassword
        );
        
        $resData = $this->curlRequest($url, $header_data, $post_data, false, true);
        // var_dump($resData) ;
        $resArr = json_decode($resData, true);
        // var_dump($resArr) ;
        return $resArr['access_token'];
    }

    /* 請求創建玩家接口 add by tlt */
    public function requstCreatePlayer(array $data)
    {
        /* 获取游戏平台信息 add by tlt */
        $parent_id = "2844210";
        $password = "123456";
        $gameC = new GameController();
        $username = $gameC->generate_value(6);
        $username = $data['pre_info'] . $username;
        $ext_ref = $username;
        // 设置 body
        $body = array(
            'parent_id' => $parent_id,
            'username' => $username,
            'password' => $password,
            'ext_ref' => $ext_ref
        );
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($data['url'], $data['header_data'], $jsonBody, false, true);
        return json_decode($resData, true);
    }

    /* 获取当前游戏平台id add by tlt */
    public function getGamePlatInfo()
    {
        $main_game_plat_code = MainGamePlat::MG; // 游戏平台代码
        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $main_game_plat_code)->first();
        return $mainGamePlatInfo;
    }

    /*
     * 创建玩家 add by tlt 测试接口
     * 创建用户入库： $update = $request->only(['main_game_plat_id','player_id','account_user_name','amount','extra_field']);
     * PlayerGameAccount::firstOrCreate($update);
     */
    public function createMember()
    {
        $url = 'https://api.adminserv88.com/v1/account/member';
        $access_token = $this->login();
        $parent_id = "2844210";
        $password = "123456";
        $mainGamePlatInfo = $this->getGamePlatInfo();
        // 用戶名前缀信息=前缀+运营商id+'Z'+用户名
        $pre_info = $mainGamePlatInfo->account_pre . $mainGamePlatInfo->main_game_plat_id . 'Z';
        
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        $data = array(
            'url' => $url,
            'access_token' => $access_token,
            'header_data' => $header_data,
            'pre_info' => $pre_info
        );
        
        do {
            $resData = $this->requstCreatePlayer($data);
        } while (isset($resData['error']));
        /*
         * echo "createUser:" ;
         * var_dump($resData) ;
         */
        
        $main_game_plat_id = $mainGamePlatInfo->main_game_plat_id;
        // 当前登录后台用户id
        $player_id = \WinwinAuth::memberUser()->player_id;
        $extra_field = $resData['data']['ext_ref'];
        $account_user_name = $resData['data']['ext_ref'];
        $PlayerGameAccountObj = new PlayerGameAccount();
        $PlayerGameAccountObj->account_user_name = $account_user_name;
        $PlayerGameAccountObj->main_game_plat_id = $main_game_plat_id;
        $PlayerGameAccountObj->player_id = $player_id;
        $PlayerGameAccountObj->extra_field = $extra_field;
        $PlayerGameAccountObj->save();
        return $account_user_name;
    }

    /* 更新密码 add by tlt 测试接口 */
    public function updatePassword()
    {
        $url = 'https://api.adminserv88.com/v1/account/member/password';
        $access_token = $this->login();
        $ext_ref = $this->getCurrentPlayerAccount()->account_user_name;
        // $ext_ref = "tlt1231" ;
        $password = "1234qwer";
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        // 设置 body
        $body = array(
            'ext_ref' => $ext_ref,
            'password' => $password
        );
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($url, $header_data, $jsonBody, true);
        var_dump($resData);
    }

    /* 依照 ID 或 REF 取得账号信息 add by tlt 测试接口 */
    public function getAccount()
    {
        $access_token = $this->login();
        $ext_ref = $this->getCurrentPlayerAccount()->account_user_name; // 额外账号，默认和游戏账号相同
        $url = 'https://api.adminserv88.com/v1/account?ext_ref=' . $ext_ref;
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 取得目前所有玩家 add by tlt 测试接口 */
    public function listChildAccounts()
    { // 该接口需要使用公网测试
        $account_id = "2844210"; // account_id即为：parentid
        $url = 'https://api.adminserv88.com/v1/account/' . $account_id . '/children?page=1';
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 获取当前玩家游戏账号 add by tlt */
    public function getCurrentPlayerAccount()
    {
        // 当前登录后台用户id
        $condition['player_id'] = \WinwinAuth::memberUser()->player_id; // 当前登录后台用户的id
        $gamePlatInfo = $this->getGamePlatInfo();
        $condition['main_game_plat_id'] = $gamePlatInfo->main_game_plat_id;
        $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
        return $playerGameAccountInfo;
    }

    // 保存转账记录
    public function saveTransferLog($money, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerAccountBalance, $carrierRemainQuota, $main_account_amount = 0, $player_id = '',
        $main_game_plat_id = '', $OpTransId = '')
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
        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $totalBalance . "; 游戏平台原余额：" . $prePlayerAccountBalance . ", 现余额：" . $afterPlayerAccountBalance;
        
        if ($playaccountlog->save() === false) {
            \DB::rollBack();
            echo "保存转账日志记录失败";
            return false;
        }
        
        // 更新当前网站所属运营商的积分
        $res = \WinwinAuth::currentWebCarrier()->update(array(
            'remain_quota' => $carrierRemainQuota
        ));
        if ($res === false) {
            \DB::rollBack();
            echo "更新运营商积分失败";
            return false;
        }
        
        // 更新转账过程中的转账状态表 ,1:表示转账成功
        if (PlayerTransfer::where('transid', $OpTransId)->update(array(
            'state' => 1
        )) === false) {
            \DB::rollBack();
            echo "更新转账结果状态失败";
            return false;
        }
        ;
        
        if (empty($player_id)) { // 前台转账
                                 // 更新用户表主账号余额数据
            $res = \WinwinAuth::memberuser()->update(array(
                'main_account_amount' => $totalBalance
            ));
            if ($res === false) {
                \DB::rollBack();
                echo "更新用户表主账号余额数据失败";
                return false;
            }
        } else { // 后台转账
            Player::where('player_id', $player_id)->update(array(
                'main_account_amount' => $totalBalance
            ));
        }
        \DB::commit();
        return true;
    }

    /* 创建转帐 add by tlt 测试接口 */
    public function createTransaction($type, $amount, $playerAccount)
    {
        $url = 'https://api.adminserv88.com/v1/transaction';
        $access_token = $this->login();
        // $account_ext_ref = "tlt1231" ; //额外参考账号名,由于我在创建玩家时就设置额外参考账号名与账号名一致，所以这里就是账号名
        if (empty($playerAccount)) { // 前台转账
            $account_ext_ref = $this->getCurrentPlayerAccount()->account_user_name;
            $player_id = \WinwinAuth::memberUser()->player_id;
            $player = Player::where('player_id', $player_id)->first();
            $totalBalance = $player->main_account_amount;
            
            $main_game_plat_id = "";
            $preMainAccountAmount = "";
        } else { // 后台转账
            $account_ext_ref = $playerAccount;
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
        
        // 保存转账过程
        $OpTransId = time() . mt_rand(10000000, 99999999);
        $playerTransfer = new PlayerTransfer();
        $playerTransfer->transid = $OpTransId;
        $playerTransfer->player_id = $player_id;
        $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $playerTransfer->main_game_plats_id = $this->getGamePlatInfo()->main_game_plat_id;
        $playerTransfer->money = $amount;
        
        if (empty($account_ext_ref)) { // 账号不存在
            echo "游戏账号不存在";
            return;
        }
        $prePlayerAccountBalance = $this->getBalance($account_ext_ref); // 游戏账号原余额
        if ($type == "DEBIT") { // 游戏账号转入主账号
            if ($amount > $prePlayerAccountBalance) {
                echo "游戏账号余额不足";
                return;
            }
            // （游戏账号转入主账号）
            $msg = 'MG平台 转到 主账户';
            $totalBalance = $preMainAccountAmount + $amount; // 主账号现余额
            $afterPlayerPlatBalance = $prePlayerAccountBalance - $amount; // 游戏账号现余额
            $carrierRemainQuota += $amount; // 主账号转到游戏账号积分减少,游戏账号转到主账号积分增加
            $playerTransfer->direction = 2; // 游戏账号转入主账号
        } else { // 转入（主账号转入游戏账号）
            $carrierRemainQuota -= $amount; // 主账号转到游戏账号积分减少,游戏账号转到主账号积分增加
            if ($carrierRemainQuota < 0) {
                echo "运营商积分不足，不能充值";
                return;
            }
            $msg = '主账户 转到 MG平台';
            $totalBalance = $preMainAccountAmount - $amount; // 主账号现余额
            $afterPlayerPlatBalance = $prePlayerAccountBalance + $amount; // 游戏账号现余额
            $playerTransfer->direction = 1; // 主账号转入游戏账号
        }
        
        $str = date('Y:m:d H:i:s', time()); // 当前时间
        $external_ref = str_replace(" ", "", str_replace(':', '', $str));
        // $external_ref = "201711271512" ;
        // $amount = "1.00" ;
        // $type = "CREDIT" ; //CREDIT 为转入，DEBIT 为转出
        $balance_type = "CREDIT_BALANCE";
        $category = "TRANSFER";
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        // 设置 body
        $body = array(
            // 'account_ext_ref' => $account_ext_ref ,
            'account_ext_ref' => $account_ext_ref,
            'external_ref' => $external_ref,
            'amount' => $amount,
            'type' => $type,
            'balance_type' => $balance_type,
            'category' => $category
        );
        $transactionBody = array(
            $body
        );
        $jsonBody = json_encode($transactionBody);
        $temp = $this->curlRequest($url, $header_data, $jsonBody, false, true);
        try {
            
            \DB::beginTransaction();
            if (! $temp) {
                if ($type != "DEBIT") {
                    $PlayerGameAccountObj = new PlayerGameAccount();
                    $PlayerGameAccountObj->where('account_user_name', $account_ext_ref)->update(
                        array(
                            'amount' => $afterPlayerPlatBalance
                        ));
                    $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerPlatBalance, $carrierRemainQuota, $preMainAccountAmount, $player_id,
                        $main_game_plat_id, $OpTransId);
                }
            } else {
                $resData = json_decode($temp, true);
                // 更新本地游戏账号余额
                if (isset($resData['error'])) {
                    $playerTransfer->state = 2;
                } else if (isset($resData['data'])) {
                    $PlayerGameAccountObj = new PlayerGameAccount();
                    $PlayerGameAccountObj->where('account_user_name', $account_ext_ref)->update(
                        array(
                            'amount' => $afterPlayerPlatBalance
                        ));
                    
                    $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerPlatBalance, $carrierRemainQuota, $preMainAccountAmount, $player_id,
                        $main_game_plat_id, $OpTransId);
                    $playerTransfer->state = 1;
                }
                $playerTransfer->save();
                \DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /* 查询转帐 add by tlt 测试接口 */
    public function verifyTransaction()
    {
        $account_ext_ref = "tlt1231"; // 额外账号
        $ext_ref = "201711271137"; // 账单号，需要根据这个查询转账记录
        $url = 'https://api.adminserv88.com/v1/transaction?account_ext_ref=' . $account_ext_ref . '&ext_ref=' . $ext_ref;
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 查询余额 add by tlt 测试接口 */
    public function getBalance($playerAccount = '')
    {
        if (empty($playerAccount)) {
            $account_ext_ref = $this->getCurrentPlayerAccount()->account_user_name;
        } else {
            $account_ext_ref = $playerAccount;
        }
        \WLog::info("getMGBalance******account_ext_ref=" . $account_ext_ref);
        
        $url = 'https://api.adminserv88.com/v1/wallet?account_ext_ref=' . $account_ext_ref;
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = json_decode($this->curlRequest($url, $header_data), true);
        /*
         * echo "<pre>" ;
         * var_dump($resData) ;
         * echo "</pre>" ;
         */
        \Log::info('MG 查询游戏余额返回数据', $resData);
        if (empty($resData['data'])) {
            return 0;
        }
        $balance = $resData['data'][0]['credit_balance'];
        if (isset($balance)) {
            // 更新本地游戏账号余额
            $PlayerGameAccountObj = new PlayerGameAccount();
            $PlayerGameAccountObj->where('account_user_name', $account_ext_ref)->update(array(
                'amount' => $balance
            ));
            return $balance;
        }
    }

    /* 进入启动游戏执行游戏 add by tlt 测试接口 */
    public function launchItem($item_id, $app_id, $flag = true)
    {
        $url = 'https://api.adminserv88.com/v1/launcher/item';
        $access_token = $this->login();
        // $ext_ref = "tlt1231" ; //额外参考账号名，与 account_id 择一使用
        $condition['player_id'] = \WinwinAuth::memberUser()->player_id; // 当前登录后台用户的id
        $gamePlatInfo = $this->getGamePlatInfo();
        $condition['main_game_plat_id'] = $gamePlatInfo->main_game_plat_id;
        // 根据用户id,和游戏平台id查询当前登录用户的所在平台的账号
        $PlayerGameAccountObj = new PlayerGameAccount();
        $userInfo = $PlayerGameAccountObj->where($condition)->first(); // 查询登录当前后台用户的mg平台游戏账号信息
        /*
         * var_dump($userInfo) ;
         * echo "<hr/>" ;
         */
        if (empty($userInfo)) { // 没有游戏账号，创建游戏账号
            $ext_ref = $this->createMember();
        } else {
            $ext_ref = $userInfo->account_user_name;
            if ($userInfo->is_need_repair) {
                // 判断游戏是否维护中
                throw new GameGateWayRuntimeException('当前游戏维护中');
            }
        }
        // return ;
        /*
         * $item_id = "1892" ; //游戏代码，游戏列表获得
         * $app_id = "1002" ; //平台代码，游戏列表获得
         */
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        // 设置 body
        $login_context = array(
            'lang' => 'zh_CN'
        );
        $body = '';
        if ($flag) {
            $body = array(
                'ext_ref' => $ext_ref,
                'item_id' => $item_id,
                'app_id' => $app_id,
                'login_context' => $login_context
            );
        } else {
            $body = array(
                'demo' => true,
                'item_id' => $item_id,
                'app_id' => $app_id,
                'login_context' => $login_context
            );
        }
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($url, $header_data, $jsonBody, false, true);
        $resArr = json_decode($resData, true);
        \Log::info('MG跳转平台========', [
            $resArr
        ]);
        if (! empty($resArr) && ! empty($resArr['data'])) {
            $url = $resArr['data'];
            Header("Location:$url");
        }
        abort(404);
    }

    /*
     * 产生游戏注单详情的连结 add by tlt 测试接口
     * 使用此界面取得单注游戏的详细记录
     */
    public function getGameNoteDetailLink()
    {
        $transaction_id = "2927956876"; // 游戏与转帐纪录游戏纪录 (依照 roundId 分类)返回的 id 值
                                        // $url = 'https://api.adminserv88.com/v1/launcher/tx/TEXT-TX-ID?lang=zh-CN';
        $url = 'https://api.adminserv88.com/v1/launcher/tx/' . $transaction_id . '?lang=zh-CN';
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
    }

    /* 获取游戏与转帐记录 add by tlt 测试接口 */
    public function getGamesAndTransferRecords($start_time = null, $end_time = null)
    {
        $company_id = "2844210"; // 同 ParentAccountId（parentid），由我们提供
        if (! isset($start_time) or ! isset($end_time)) { // 当没有查询时间范围时，默认查询2小时内的数据
            $start_time = str_replace(' ', 'T', date("Y-m-d H:i:s", time() - 3600 * 2)); // 当前时间2小时前的时间
            $end_time = str_replace(' ', 'T', date("Y-m-d H:i:s", time())); // 当前时间
        }
        $include_transfers = true; // 是否包含转帐纪录，预设为 true
        $include_end_round = true;
        $page = 1; // 欲查询的页数，预设为 1
        $url = 'https://api.adminserv88.com/v1/feed/transaction?company_id=' . $company_id . '&start_time=' . $start_time . '&end_time=' . $end_time . '&include_transfers=' .
             $include_transfers . '&include_end_round=' . $include_end_round . '&page=' . $page;
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $resData = $this->curlRequest($url, $header_data);
        var_dump($resData);
        return $resData;
    }

    /* 修改账号参数 (账号禁用、启用) add by tlt 测试接口 */
    public function editAccount()
    {
        $account_id = "3464612"; // 玩家账号id
        $url = 'https://api.adminserv88.com/v1/account/' . $account_id;
        $access_token = $this->login();
        $name = "tlt1231"; // 赋予玩家新的显示名称
        $ext_ref = "tlt1231"; // 赋予玩家新的 ext_ref
        $version = 1;
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        // 设置 body
        $body = array(
            'name' => $name,
            'ext_ref' => $ext_ref,
            'ip_whitelist' => true,
            'status' => 'ENABLED',
            'version' => $version
        );
        $jsonBody = json_encode($body);
        $resData = $this->curlRequest($url, $header_data, $jsonBody, true, true);
        var_dump($resData);
    }

    /* 游戏纪录 (依照 roundId 分类) add by tlt 测试接口 */
    public function getGamesRecord($start_time = null, $end_time = null, $page = 1, $page_size = 1000)
    {
        $company_id = "2844210"; // 同 ParentAccountId（parentid），由我们提供
        if (! isset($start_time) or ! isset($end_time)) { // 当没有查询时间范围时，默认查询2小时内的数据
            $start_time = str_replace(' ', 'T', date("Y-m-d H:i:s", time() - 3600 * 1000)); // 当前时间1000小时前的时间
            $end_time = str_replace(' ', 'T', date("Y-m-d H:i:s", time())); // 当前时间
        }
        $url = 'https://api.adminserv88.com/v1/feed/transactionround?company_id=' . $company_id . '&start_time=' . $start_time . '&end_time=' . $end_time . '&page=' . $page .
             '&page_size=' . $page_size;
        $access_token = $this->login();
        // 设置 Header
        $header_data = array();
        $header_data[] = 'Authorization: Bearer ' . $access_token;
        $header_data[] = 'X-DAS-TZ: UTC+8';
        $header_data[] = 'X-DAS-CURRENCY: CNY';
        $header_data[] = 'X-DAS-LANG: zh-CN';
        $header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header_data[] = 'Content-Type: application/json;charset=UTF-8';
        $resData = json_decode($this->curlRequest($url, $header_data), true);
        /*
         * echo "<pre>" ;
         * //var_dump($resData) ;
         * echo "</pre>" ;
         */
        return $resData;
    }

    /**
     * ** 获取最后一次同步时间 add by tlt ****
     */
    public function lastSynchronizedGameFlowTimeStamp()
    {
        // 获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if (! $this->lastSynchronizeTime && \Storage::exists('MG_synchronizedTimestamp')) {
            $this->lastSynchronizeTime = \Storage::get('MG_synchronizedTimestamp');
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
    public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null, $page)
    {
        $start_time = str_replace(' ', 'T', $condition->start_date);
        $end_time = str_replace(' ', 'T', $condition->end_date);
        /*
         * $start_time = str_replace(' ','T',date("Y-m-d H:i:s",time()-3600*10000)) ; //当前时间1000小时前的时间
         * $end_time = str_replace(' ','T',date("Y-m-d H:i:s",time())) ; //当前时间
         */
        try {
            $response = $this->getGamesRecord($start_time, $end_time, $page);
            $result = $response;
            if ($result['data']) {
                $dataArr = array();
                foreach ($result['data'] as $k => $value) {
                    // 过滤没有投注金额的投注记录
                    if ($value['sum_of_wager'] || $value['sum_of_payout']) { // "sum_of_wage:下注额 ; sum_of_payout:派彩，不扣除本金
                        $dataArr[] = $value;
                    }
                }
                $flowRecord = array();
                $i = 0;
                // var_dump($flowRecord) ; echo "print_data:<br>" ;
                foreach ($dataArr as $element) {
                    $flowRecord[$i]['playerName'] = $element['account_ext_ref'];
                    $flowRecord[$i]['gameCode'] = $element['meta_data']['item_id']; // 游戏代码
                    $flowRecord[$i]['bet'] = $element['sum_of_wager'];
                    $flowRecord[$i]['win'] = $element['sum_of_payout'];
                    if ($element['status'] == "open") {
                        $flowRecord[$i]['status'] = 1;
                    } else {
                        $flowRecord[$i]['status'] = 0;
                    }
                    
                    $flowRecord[$i]['date'] = $element['start_time'];
                    $flowRecord[$i]['plat_game_code'] = $element['application_id'];
                    $flowRecord[$i]['code'] = $element['id']; // 游戏流水号
                    $flowRecord[$i]['game_id'] = $element['meta_data']['mg']['game_id'];
                    if ($element['sum_of_refund'] > 0) { // sum_of_refund 退费，可能会依网络问题造成投注失败
                        $flowRecord[$i]['isBetAvailable'] = false; // 无效投注
                        $flowRecord[$i]['availableBet'] = 0;
                    } else {
                        $flowRecord[$i]['isBetAvailable'] = true; // 有效投注
                        $flowRecord[$i]['availableBet'] = $element['sum_of_wager']; // 有效投注额
                    }
                    $i ++;
                }
                return $flowRecord;
            }
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新同步时间到磁盘
     *
     * @param
     *            $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time)
    {
        \Storage::put('MG_synchronizedTimestamp', $time);
    }

    /**
     *
     * @param GameGatewayBetFlowRecord[] $betFlowRecords
     */
    // 数据同步保存到硬盘上
    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition, PlayerGameAccount $gameAccount = null)
    {
        $page = 0;
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
                    $game = $GameObj->where('game_type', $record['plat_game_code'])->first();
                    if (! $game) {
                        throw new GameGateWayRuntimeException('无法找到游戏码:' . $record['gameCode'] . " 对应的游戏");
                    }
                    
                    $playerBetFlow->game_id = $record['game_id'];
                    
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
                    
                    \DB::transaction(
                        function () use ($playerGameAccount, $playerBetFlow) {
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
                    
                    \WLog::info('====>会员投注记录同步成功:',
                        [
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
}