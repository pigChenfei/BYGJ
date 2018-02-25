<?php
namespace App\Vendor\GameGateway\PT;

use App\Models\Def\Game;
use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGateway;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewayLoginEntity;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use App\Http\Controllers\Web\GameController;
use App\Models\Log\PlayerAccountLog;
use App\Models\PlayerTransfer;
use Models\Log\GameNoneExists;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/10
 * Time: 下午4:39
 */
class PTGameGateway extends GameGateway
{

    /**
     *
     * @var MainGamePlat
     */
    private $mainGamePlat;

    private $lastSynchronizeTime;

    public static function registerRoutes()
    {
        return [ // 'ptLoginRedirect' => 'PTGameRedirectController@index'
];
    }

    /**
     *
     * @return string
     */
    public static function getMainGamePlatCode()
    {
        return 'pt';
    }

    /**
     *
     * @return MainGamePlat
     */
    public function getMainGamePlat()
    {
        if (! $this->mainGamePlat) {
            $this->mainGamePlat = MainGamePlat::where('main_game_plat_code', self::getMainGamePlatCode())->firstOrFail();
        }
        return $this->mainGamePlat;
    }

    /* 获取运营商平台id add by tlt */
    public function getGamePlatInfo()
    {
        $main_game_plat_code = MainGamePlat::PT; // 游戏平台代码
        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $main_game_plat_code)->first();
        return $mainGamePlatInfo;
    }

    /**
     *
     * @param Player $user            
     * @return PlayerGameAccount
     */
    public function getPlayerAccount(Player $user)
    {
        $account = parent::getPlayerAccount($user);
        // 由于PT平台登录时用的账号是PT返回的账号和密码 而不是我们生成的账号和密码,因此我们在额外字段里面检测该用户是否存在PT的账户和密码;
        $ptGameAccount = new PTGameAccount($account);
        // var_dump($ptGameAccount->loginUserName(),$ptGameAccount->loginPassword()) ;return ;
        // 如果不存在用户登录信息,则创建用户
        if (! $ptGameAccount->loginUserName() || ! $ptGameAccount->loginPassword()) {
            $builder = new PTGameGatewayQueryBuilder();
            $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_CREATE);
            /* 随机生成用户名 add by tlt start */
            do {
                $mainGamePlatInfo = $this->getGamePlatInfo();
                // 用戶名前缀信息=前缀+运营商id+'Z'+用户名
                $pre_info = $mainGamePlatInfo->account_pre . $mainGamePlatInfo->main_game_plat_id . 'Z';
                $gameC = new GameController();
                $username = $pre_info . $gameC->generate_value(6); // 六位随机账号
                $builder->addParameters(
                    [
                        // 'playername' => $builder->PREFIX.$account->account_user_name,
                        'playername' => $builder->PREFIX . $username,
                        'adminname' => $builder->AdminName,
                        'kioskname' => $builder->KioskName,
                        'custom02' => $builder->AdminName
                    ]);
                try {
                    $response = $builder->fetch();
                } catch (\Exception $e) {
                    $response = array();
                    throw $e;
                }
            } while (empty($response));
            /* 随机生成用户名 add by tlt end */
            
            $data = $response->getResponseData();
            // 如果用户成功创建,则将用户数据存入数据库中;
            $ptGameAccount->setLoginPassword($data['result']['password']);
            $ptGameAccount->setLoginUserName($data['result']['playername']);
            $ptGameAccount->playerGameAccount->account_user_name = $data['result']['playername'];
            $ptGameAccount->playerGameAccount->save();
            return $ptGameAccount->playerGameAccount;
        }
        return $account;
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     * @return GameGatewayLoginEntity
     */
    public function loginPageEntity(PlayerGameAccount $gameAccount, $ptGameCode)
    {
        // 判断游戏是否维护中
        if ($gameAccount->is_need_repair) {
            throw new GameGateWayRuntimeException('当前游戏维护中');
        }
        $entity = new GameGatewayLoginEntity();
        /* var_dump($entity) ; */
        $entity->scripts = __DIR__ . '/LoginView/scripts.blade.php';
        $entity->dom = __DIR__ . '/LoginView/dom.blade.php';
        $entity->gameCode = $ptGameCode;
        $entity->gameAccount = $gameAccount;
        /*
         * echo "<hr/>" ;
         * echo "<pre>" ;
         * var_dump($entity) ;
         * echo "</pre>" ;
         */
        return $entity;
    }

    /**
     *
     * @param PlayerGameAccount $user            
     */
    public function logout(PlayerGameAccount $gameAccount)
    {
        $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_LOGOUT);
        $builder->addParameters([
            'playername' => $ptGameAccount->loginUserName()
        ]);
        try {
            return $builder->fetch();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // 保存转账记录
    public function saveTransferLog($money, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerAccountBalance, 
        $carrierRemainQuota, $player_id = '', $mainAccountAmount = 0, $main_game_plat_id = '', $OpTransId = '')
    {
        \DB::beginTransaction();
        // 写日志表
        $playaccountlog = new PlayerAccountLog();
        $playaccountlog->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $playaccountlog->player_id = empty($player_id) ? \WinwinAuth::memberUser()->player_id : $player_id;
        $playaccountlog->main_game_plat_id = empty($main_game_plat_id) ? $this->getGamePlatInfo()->main_game_plat_id : $main_game_plat_id;
        ;
        $playaccountlog->amount = $money;
        $playaccountlog->fund_type = 5;
        $playaccountlog->operator_reviewer_id = NULL;
        
        // 转账前主账号余额
        $preMainAccount = empty($mainAccountAmount) ? \WinwinAuth::memberUser()->main_account_amount : $mainAccountAmount;
        
        $playaccountlog->fund_source = $msg;
        // echo $totalBalance."\n";
        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $totalBalance . "; 游戏平台原余额：" .
             $prePlayerAccountBalance . ", 现余额：" . $afterPlayerAccountBalance;
        
        if ($playaccountlog->save() === false) {
            \DB::rollBack();
        }
        
        // 更新当前网站所属运营商的积分
        $res = \WinwinAuth::currentWebCarrier()->update(
            array(
                'remain_quota' => $carrierRemainQuota
            ));
        if ($res === false) {
            \DB::rollBack();
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
                // \DB::rollBack() ;
                echo "更新主账号余额失败";
                return;
            }
        } else { // 后台转账
            \DB::update('update inf_player set main_account_amount=? where player_id=?', 
                array(
                    $totalBalance,
                    $player_id
                ));
            // Player::where('player_id',$player_id) ->update(array('main_account_amount' => $totalBalance)) ;
        }
        \DB::commit();
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     * @param float $amount            
     * @return \App\Vendor\GameGateway\Query\QueryResult
     * @throws \Exception
     */
    // 主账号转到游戏账号
    public function depositToPlayerGameAccount(PlayerGameAccount $gameAccount, $amount)
    {
        $ptGameAccount = new PTGameAccount($gameAccount);
        $playerAcountInfo = PlayerGameAccount::where('account_user_name', $ptGameAccount->loginUserName())->first();
        $player_id = $playerAcountInfo->player_id;
        $main_game_plat_id = $playerAcountInfo->main_game_plat_id;
        $totalBalance = Player::where('player_id', $player_id)->first()->main_account_amount; // 主账号余额
        $mainAccountAmount = $totalBalance;
        // $totalBalance = \WinwinAuth::memberuser()->main_account_amount ; //主账号原余额
        if ($amount > $totalBalance) {
            echo "主账号余额不足";
            return;
        }
        // 查询当前网站所属运营商的积分
        $carrierRemainQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
        $carrierRemainQuota -= $amount; // 主账号转到游戏账号，运营商积分减少
        if ($carrierRemainQuota <= 0) { // 运营商积分不足
            echo "运营商积分不足，不允许充值";
            return;
        }
        parent::depositToPlayerGameAccount($gameAccount, $amount);
        // $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_DEPOSIT);
        $builder->addParameters(
            [
                'playername' => $ptGameAccount->loginUserName(),
                'amount' => $amount,
                'adminname' => $builder->AdminName
            ]);
        try {
            \DB::beginTransaction();
            $prePlayerAccountBalance = $this->fetchPlayerBalanceInfo($gameAccount); // 游戏账号原余额
            $resData = json_decode(json_encode($builder->fetch()), true);
            if ((isset($resData) && $resData['data']['result']['currentplayerbalance'])) { // 请求接口成功
                $msg = '主账户 转到 PT平台';
                $totalBalance -= $amount; // 主账号现余额
                $afterPlayerPlatBalance = $prePlayerAccountBalance + $amount; // 游戏平台现余额
                                                                              // 更新本地游戏账号余额
                $PlayerGameAccountObj = new PlayerGameAccount();
                $PlayerGameAccountObj->where('account_user_name', $ptGameAccount->loginUserName())
                    ->update(array(
                    'amount' => $afterPlayerPlatBalance
                ));
                
                // 保存转账过程
                $OpTransId = time() . mt_rand(10000000, 99999999);
                $playerTransfer = new PlayerTransfer();
                $playerTransfer->transid = $OpTransId;
                $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                $playerTransfer->player_id = $player_id;
                $playerTransfer->main_game_plats_id = $this->getGamePlatInfo()->main_game_plat_id;
                $playerTransfer->money = $amount;
                $playerTransfer->direction = 1; // 主账号转入游戏账号
                if ($playerTransfer->save() === false) {
                    \DB::rollBack();
                    \WLog::info("保存转账过程状态失败");
                    return;
                }
                
                $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerPlatBalance, 
                    $carrierRemainQuota, $player_id, $mainAccountAmount, $main_game_plat_id, $OpTransId);
            }
            \DB::commit();
            return $resData;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     * @param float $amount            
     * @return bool
     */
    // 游戏账号转到主账号
    public function withdrawFromPlayerGameAccount(PlayerGameAccount $gameAccount, $amount)
    {
        $prePlayerAccountBalance = $this->fetchPlayerBalanceInfo($gameAccount); // 游戏账号原余额
        if ($amount > $prePlayerAccountBalance) {
            echo "游戏账号余额不足";
            return;
        }
        parent::withdrawFromPlayerGameAccount($gameAccount, $amount); // 这里调用父类方法检查余额是否充足
        $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_WITHDRAW);
        $builder->addParameters(
            [
                'playername' => $ptGameAccount->loginUserName(),
                'amount' => $amount,
                'adminname' => $builder->AdminName
            ]);
        // 先踢线
        $builderKick = new PTGameGatewayQueryBuilder();
        $builderKick->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_LOGOUT);
        $builderKick->addParameters([
            'playername' => $ptGameAccount->loginUserName()
        ]);
        $builderKick->fetch();
        //
        try {
            \DB::beginTransaction();
            /* 根据游戏账号查询游戏账号详细信息 add by tlt */
            $playerAcountInfo = PlayerGameAccount::where('account_user_name', $ptGameAccount->loginUserName())->first();
            $player_id = $playerAcountInfo->player_id;
            $main_game_plat_id = $playerAcountInfo->main_game_plat_id;
            $totalBalance = Player::where('player_id', $player_id)->first()->main_account_amount;
            // $totalBalance = \WinwinAuth::memberuser()->main_account_amount ; //主账号余额
            $mainAccountAmount = $totalBalance;
            $msg = 'PT平台 转到 主账号';
            
            $totalBalance += $amount;
            $afterPlayerPlatBalance = $prePlayerAccountBalance - $amount; // 游戏平台现余额
                                                                          
            // 保存转账过程
            $OpTransId = time() . mt_rand(10000000, 99999999);
            $playerTransfer = new PlayerTransfer();
            $playerTransfer->transid = $OpTransId;
            $playerTransfer->player_id = $player_id;
            $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $playerTransfer->main_game_plats_id = $this->getGamePlatInfo()->main_game_plat_id;
            $playerTransfer->money = $amount;
            $playerTransfer->direction = 2; // 游戏账号转入主账号
            if ($playerTransfer->save() === false) {
                \DB::rollBack();
                \WLog::info("保存转账过程状态失败");
                return;
            }
            
            // 查询当前网站所属运营商的积分
            $carrierRemainQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
            $carrierRemainQuota += $amount; // 游戏账号转到主账号，运营商额度增加
            $res = json_decode(json_encode($builder->fetch()), true);
            if ((isset($resData) && $resData['currentplayerbalance']) or empty($resData)) { // 请求接口成功,转账成功或者中途断网我们本地都会先扣除金额
                                                                                            // 更新本地游戏账号余额
                $PlayerGameAccountObj = new PlayerGameAccount();
                $PlayerGameAccountObj->where('account_user_name', $ptGameAccount->loginUserName())
                    ->update(array(
                    'amount' => $afterPlayerPlatBalance
                ));
                
                $this->saveTransferLog($amount, $totalBalance, $msg, $prePlayerAccountBalance, $afterPlayerPlatBalance, 
                    $carrierRemainQuota, $player_id, $mainAccountAmount, $main_game_plat_id, $OpTransId);
            }
            \DB::commit();
            return $res;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
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
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_GAMES_BETTING_FLOW);
        $builder->addParameters(
            [
                'startdate' => urlencode($condition->start_date),
                'enddate' => urlencode($condition->end_date),
                'showinfo' => 1,
                'frozen' => 'all',
                'perPage' => 50000,
                'page' => 1
            ]);
        if ($gameAccount) {
            $ptGameAccount = new PTGameAccount($gameAccount);
            $builder->addParameter('playername', $ptGameAccount->loginUserName());
        }
        try {
            $response = $builder->fetch();
            $data = $response->getResponseData();
            $result = $data['result'];
            if ($result) {
                // 过滤没有投注金额的投注记录
                $result = array_filter($result, 
                    function ($element) {
                        return ! ($element['BET'] == 0 && $element['WIN'] == 0);
                    });
                return array_map(
                    function ($element) {
                        $flowRecord = new GameGatewayBetFlowRecord();
                        $flowRecord->playerName = $element['PLAYERNAME'];
                        $flowRecord->gameType = $element['GAMETYPE'];
                        preg_match('/\(\w+\)/', $element['GAMENAME'], $matches);
                        if (! $matches) {
                            throw new GameGateWayRuntimeException('无法匹配到游戏代码:' . $element['GAMENAME']);
                        }
                        $flowRecord->gameName = $element['GAMENAME'];
                        $flowRecord->gameCode = substr($matches[0], 1, strlen($matches[0]) - 2);
                        $flowRecord->bet = $element['BET'];
                        $flowRecord->win = $element['WIN'];
                        $flowRecord->progressiveBet = $element['PROGRESSIVEBET'];
                        $flowRecord->progressiveWin = $element['PROGRESSIVEWIN'];
                        $flowRecord->balance = $element['BALANCE'];
                        $flowRecord->currentBet = $element['CURRENTBET'];
                        $flowRecord->date = $element['GAMEDATE'];
                        $flowRecord->code = $element['GAMECODE'];
                        // 是否投注流水有效
                        // 如果是老虎机, 那么都是有效流水
                        // 如果是真人游戏, 判断是投的庄家还是闲家, 如果庄家和闲家投注一样, 那么也是无效投注流水, 如果是和局那么是无效投注流水, 如果庄家和闲家投注都投了, 选取他们的差额作为有效投注流水.
                        $flowRecord->betInfo = $element['INFO'];
                        // 真人游戏正则;
                        $baccaratGameInfoReg = '/(([A-Z]([A-Z]|\d+))(\s[A-Z]([A-Z]|\d+))+;)(\d+\,){8}/';
                        preg_match($baccaratGameInfoReg, $element['INFO'], $matches);
                        if ($matches) {
                            $this->baccaratGameAnalysis($flowRecord, $matches[0]);
                        } else {
                            $flowRecord->isBetAvailable = true;
                            $flowRecord->availableBet = $flowRecord->bet;
                        }
                        return $flowRecord;
                    }, $result);
            }
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function baccaratGameAnalysis(GameGatewayBetFlowRecord &$record, $matchInfoString)
    {
        $betFlowDetailReg = '/\d+(\,\d+){7}/';
        preg_match($betFlowDetailReg, $matchInfoString, $flowBetDetailMatches);
        $betFlowDetailArray = explode(',', $flowBetDetailMatches[0]);
        // 庄家投注, 第三位
        $bankerBet = $betFlowDetailArray[2];
        // 庄家派彩, 第四位
        $bankerWin = $betFlowDetailArray[3];
        // 闲家投注, 第五位
        $playerBet = $betFlowDetailArray[4];
        // 闲家派彩, 第六位
        $playerWin = $betFlowDetailArray[5];
        // 如果庄闲投注一样,或者庄家投注和派彩一样,或者闲家投注和派彩一样说明是和局. 那么就是无效投注
        if ($bankerBet == $playerBet || ($bankerBet == $bankerWin && $bankerBet > 0) ||
             ($playerBet == $playerWin && $playerBet > 0)) {
            // :因为庄投:0 赢:0 闲投:2000 赢:0 [] []
            \WLog::info('无效投注:因为庄投:' . $bankerBet . ' 赢:' . $bankerWin . ' 闲投:' . $playerBet . ' 赢:' . $playerWin);
            $record->isBetAvailable = false;
            $record->availableBet = 0;
        } // 如果庄闲投注不一样,那么算差额为有效投注
else if ($bankerBet != $playerBet) {
            $record->isBetAvailable = true;
            $record->availableBet = abs($bankerBet - $playerBet) / 100;
        }
        if ($bankerBet && ! $playerBet) {
            $record->playerOrBanker = PlayerBetFlowLog::BET_FLOW_BANKER;
        } else if (! $bankerBet && $playerBet) {
            $record->playerOrBanker = PlayerBetFlowLog::BET_FLOW_PLAYER;
        } else if ($playerBet && $bankerBet) {
            $record->playerOrBanker = PlayerBetFlowLog::BET_FLOW_PLAYER_AND_BANKER;
        }
    }

    /**
     *
     * @param GameGatewayBetFlowRecord[] $betFlowRecords            
     */
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
        // dump($betFlowRecords);
        // dump('aaaa');
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
                $playerBetFlow->player_id = $playerGameAccount->player_id;
                // 查询到会员id即可查询到carrier_id;
                $playerBetFlow->carrier_id = $playerGameAccount->player->carrier_id;
                $game = Game::getCachedGameByGameCode($record->gameCode);
                if (! $game) {
                    $noneExists = array(
                        'game_plat' => 'PT',
                        'game_flow_code' => $record->code,
                        'game_code' => $record->gameCode,
                        'game_name' => $record->gameName,
                        'message' => '无法找到游戏:' . $record->gameCode
                    );
                    try {
                        $exists = \DB::table('log_game_none_exixts')->where(
                            [
                                'game_plat' => $noneExists['game_plat'],
                                'game_code' => $noneExists['game_code']
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
                $playerBetFlow->game_id = $game->game_id;
                $playerBetFlow->game_plat_id = $game->game_plat_id;
                $playerBetFlow->game_flow_code = $record->code;
                $playerBetFlow->bet_amount = $record->bet;
                $playerBetFlow->company_payout_amount = $record->win;
                $playerBetFlow->company_win_amount = $record->bet - $record->win;
                $playerBetFlow->bet_flow_available = $record->isBetAvailable;
                $playerBetFlow->available_bet_amount = $record->availableBet;
                $playerBetFlow->player_or_banker = $record->playerOrBanker;
                $playerBetFlow->created_at = $record->date;
                $playerBetFlow->game_status = PlayerBetFlowLog::GAME_STATUS_FINISHED;
                $playerBetFlow->progressive_bet = $record->progressiveBet;
                $playerBetFlow->progressive_win = $record->progressiveWin;
                $playerBetFlow->bet_info = $record->betInfo;
                if (! $lastSynchronizeTime || $lastSynchronizeTime < $record->date) {
                    $lastSynchronizeTime = $record->date;
                }
                \DB::transaction(
                    function () use ($playerGameAccount, $playerBetFlow) {
                        $playerGameAccount->save();
                        $playerBetFlow->save();
                    });
                $record->playerBetFlowDBId = $playerBetFlow->id;
                \WLog::info('====>会员投注记录同步成功:', 
                    [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'date' => $playerBetFlow->created_at
                    ]);
            } catch (\Exception $e) {
                \WLog::error('====>会员投注记录同步失败:', [
                    'message' => $e->getMessage()
                ]);
                throw $e;
            }
        }
        $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $lastSynchronizeTime)->addSeconds(1)->toDateTimeString();
        return $betFlowRecords;
    }

    public function lastSynchronizedGameFlowTimeStamp()
    {
        // 获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if (! $this->lastSynchronizeTime && \Storage::exists('PTGameGateway_synchronizedTimestamp')) {
            $this->lastSynchronizeTime = \Storage::get('PTGameGateway_synchronizedTimestamp');
        }
        // 如果上次的时间在28(接近30)分钟之前, 那么我们将时间强制减到20分钟 或者 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
        if (! $this->lastSynchronizeTime || $this->lastSynchronizeTime <= date('Y-m-d H:i:s', time() - 28 * 60)) {
            // 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
            $this->lastSynchronizeTime = date('Y-m-d H:i:s', time() - 20 * 60);
        }
        return $this->lastSynchronizeTime;
    }

    /**
     * 更新同步时间到磁盘
     *
     * @param
     *            $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time)
    {
        \Storage::put('PTGameGateway_synchronizedTimestamp', $time);
    }

    /**
     * 更新会员登录密码
     *
     * @param PlayerGameAccount $gameAccount            
     * @param
     *            $password
     * @throws \Exception
     */
    public function updatePlayerPassword(PlayerGameAccount $gameAccount, $password)
    {
        $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_UPDATE);
        $builder->addParameter('playername', $ptGameAccount->loginUserName()); // $builder->PREFIX.$gameAccount->player->carrier_id.$ptGameAccount->loginUserName());
        $builder->addParameter('password', $password);
        try {
            $response = $builder->fetch();
            $data = $response->getResponseData();
            $ptGameAccount->setLoginPassword($password);
            $ptGameAccount->playerGameAccount->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     */
    public function isPlayerOnline(PlayerGameAccount $gameAccount)
    {
        $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_ONLINE);
        $builder->addParameter('playername', $ptGameAccount->loginUserName());
        try {
            $response = $builder->fetch();
            $data = $response->getResponseData();
            return $data['result']['result'] == 1;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     */
    public function synchronizePlayerAccountInfo(PlayerGameAccount &$gameAccount)
    {
        $balanceInfo = $this->fetchPlayerBalanceInfo($gameAccount);
        $gameAccount->account_user_name = $balanceInfo->playerName;
        // TODO 同步账户信息,需要确定哪些字段什么意思
        $gameAccount->amount = $balanceInfo->balance;
        $gameAccount->save();
    }

    /**
     *
     * @param PlayerGameAccount $gameAccount            
     */
    public function resetLoginFailedAttempts(PlayerGameAccount $gameAccount)
    {
        // TODO: Implement resetLoginFailedAttempts() method.
    }

    /**
     */
    public function playerList()
    {
        // TODO: Implement playerList() method.
    }

    public static function loginSuccessRedirectPagePath()
    {
        return __DIR__ . '/RedirectPage/redirect.blade.php';
    }

    /**
     * 获取会员账户信息
     *
     * @param PlayerGameAccount $gameAccount            
     * @return PTGamePlayerBalance
     * @throws \Exception
     */
    public function fetchPlayerBalanceInfo(PlayerGameAccount $gameAccount)
    {
        $ptGameAccount = new PTGameAccount($gameAccount);
        $builder = new PTGameGatewayQueryBuilder();
        $builder->setApiFunction(PTGameGatewayQueryBuilder::API_PLAYER_BALANCE);
        $builder->addParameters([
            'playername' => $ptGameAccount->loginUserName()
        ]);
        try {
            $response = $builder->fetch();
            $data = $response->getResponseData();
            $balanceInfo = new PTGamePlayerBalance();
            $balanceInfo->balance = $data['result']['balance'];
            $balanceInfo->currencyCode = $data['result']['currencycode'];
            $balanceInfo->bonusBalance = $data['result']['bonusbalance'];
            $balanceInfo->rcBalance = $data['result']['rc_balance'];
            $balanceInfo->currentBet = $data['result']['current_bet'];
            $balanceInfo->playerName = $data['result']['playername'];
            $balance = $data['result']['balance'];
            if (isset($balance)) {
                // 更新本地游戏账号余额
                $PlayerGameAccountObj = new PlayerGameAccount();
                $PlayerGameAccountObj->where('account_user_name', $ptGameAccount->loginUserName())
                    ->update(array(
                    'amount' => $balance
                ));
            }
            return $balance;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}