<?php
namespace App\Vendor\GameGateway\Gateway;
use App\Models\Log\PlayerAccountLog;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use App\Vendor\GameGateway\PT\PTGameGateway;
use App\Vendor\GameGateway\MGEUR_API\MGEUR_API ;
use App\Vendor\GameGateway\Sunbet\Sunbet ;
use App\Vendor\GameGateway\VR\VR;
use App\Vendor\GameGateway\PNG\PNG;
use App\Vendor\GameGateway\TTG\TTG;
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/10
 * Time: 下午4:38
 */
class GameGatewayRunTime
{


    /**
     *测试环境
     */
    const DEVELOPMENT = 'DEVELOPMENT';
    /**
     *生产环境
     */
    const PRODUCTION  = 'PRODUCTION';

    public static $env = self::PRODUCTION;
    /**
     * @var GameGateWayInterface
     */
    private $game_gate_way;


    /**
     * @var PlayerGameAccount
     */
    private $game_player_account;

    /**
     * GameGatewayRunTime constructor.
     * @param $main_game_plat_code
     */
    public function __construct($main_game_plat_code, $env = self::PRODUCTION)
    {
        $this->game_gate_way = new self::$game_map[$main_game_plat_code]();
        self::$env = $env;
    }

    /**
     * @var array
     */
    public static $game_map = [
        'pt' => PTGameGateway::class ,
        'mg' => MGEUR_API::class ,
        'sunbet' => Sunbet::class,
        'vr' => VR::class,
        'png'=> PNG::class,
        'ttg'=> TTG::class
    ];

    public static function route(){
        $routeLists = array_map(function($interfaceClass){
            return $interfaceClass::registerRoutes();
        },array_values(self::$game_map));
        foreach($routeLists as $index => $routes){
            foreach ($routes as $uriName => $action){
                \Route::get($uriName,$action);
            }
        }
    }

    /**
     *
     */
    public function getPlayerAccount(Player $player){
        if(!$this->game_player_account){
            $this->game_player_account =  $this->game_gate_way->getPlayerAccount($player);
        }
        return $this->game_player_account;
    }


    /**
     * 获取游戏登录界面
     * @param Player $player
     * @return GameGatewayLoginEntity
     */
    public function loginPageEntity(Player $player,$gameCode){
        return $this->game_gate_way->loginPageEntity($this->getPlayerAccount($player),$gameCode);
    }

    /**
     * 游戏流水查询
     * @param Player $player
     * @return GameGatewayBetFlowRecord[]
     */
    public function gameFlow(GameGatewaySearchCondition $gameCondition,Player $player = null){
        return $this->game_gate_way->fetchGameFlowResult($gameCondition,$player ? $this->getPlayerAccount($player) : null);
    }


    /**
     * 同步游戏数据至数据库
     * @param Player|null $player
     * @return GameGatewayBetFlowRecord[]
     * @throws \Exception
     */
    public function synchronizeGameFlowToDB(Player $player = null){
        //实例化查询搜索条件
        $gameCondition = new GameGatewaySearchCondition();
        //获取同步开始时间
        $gameCondition->start_date = $this->game_gate_way->lastSynchronizedGameFlowTimeStamp();
        $endDate = $gameCondition->end_date;
        try{
            //同步游戏流水记录
            $records = $this->game_gate_way->synchronizeGameFlowToDB($gameCondition,$player);

            //不管成功还是失败都需要更新同步时间戳
            $this->game_gate_way->updateSynchronizedGameFlowTimeStamp($gameCondition->end_date);
            return $records;
        }catch (\Exception $e){
            //不管成功还是失败都需要更新同步时间戳
            $this->game_gate_way->updateSynchronizedGameFlowTimeStamp($gameCondition->end_date);
            $exception = new GameGateWaySynchronizeDBException($e->getMessage());
            //如果失败了, 那么队列中最近的时间也应该是失败之前的时间;
            $gameCondition->end_date = $endDate;
            $exception->searchCondition = $gameCondition;
            $exception->gameGateWay = $this->game_gate_way;
            throw $exception;
        }
    }


    /**
     * 同步会员账户信息至数据库
     * @param Player|null $player
     */
    public function synchronizePlayerAccountInfoToDB(Player $player = null){
        $gameAccount = $this->getPlayerAccount($player);
        $this->game_gate_way->synchronizePlayerAccountInfo($gameAccount);
    }

    /**
     * 判断会员是否在线
     * @param Player $player
     * @return bool
     */
    public function isPlayerOnline(Player $player){
        return $this->game_gate_way->isPlayerOnline($this->getPlayerAccount($player));
    }


    /**
     * 存款到游戏平台
     * @param Player $player
     * @param $amount
     * @throws \Exception
     */
    public function depositToPlayerGameAccount(Player $player, $amount){
        try{
            $gameAccount = $this->getPlayerAccount($player);
            $player->carrier->checkRemainQuotaEnough($amount);
            $this->game_gate_way->depositToPlayerGameAccount($gameAccount,$amount);
            //如果存款成功,更新会员主账户余额,游戏平台余额,运营商额度,更新会员资金流水;
            \DB::transaction(function () use ($player,$gameAccount,$amount){
                //新增会员资金流水记录
                $oldMainAccountAmount = $player->main_account_amount;
                $player->main_account_amount -= $amount;
                $nowMainAccountAmount = $player->main_account_amount;
                $oldGameAccountAmount = $gameAccount->amount;
                $gameAccount->amount += $amount;
                $nowGameAccountAmount = $gameAccount->amount;
                $player->carrier->remain_quota -= $amount;
                $player->update();
                $gameAccount->update();
                $player->carrier->update();
                $accountLog = new PlayerAccountLog();
                $accountLog->carrier_id = $player->carrier_id;
                $accountLog->remark = "主账户原余额： $oldMainAccountAmount 现余额： $nowMainAccountAmount 平台现余额： $nowGameAccountAmount"; //原余额: $oldGameAccountAmount 现余额: $nowGameAccountAmount";
                $accountLog->amount = $amount;
                //从主账户转到游戏平台
                $accountLog->fund_type = PlayerAccountLog::FUND_TYPE_TRANSFER;
                $accountLog->fund_source = '主账户 转到 '.$gameAccount->mainGamePlat->main_game_plat_name.'平台';  //PlayerAccountLog::FUND_RESOURCE_MAIN_ACCOUNT_TO_GAME_ACCOUNT;
                $accountLog->player_id = $player->player_id;
                $accountLog->main_game_plat_id = $gameAccount->main_game_plat_id;
                $accountLog->save();
            });
        }catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * 从会员游戏账户取款
     * @param Player $player
     * @param $amount
     * @throws \Exception
     */
    public function withDrawFromPlayerGameAccount(Player $player, $amount){
        try{
            $gameAccount = $this->getPlayerAccount($player);
            $this->game_gate_way->withdrawFromPlayerGameAccount($gameAccount,$amount);
            //如果取款成功,更新会员主账户余额,游戏平台余额,运营商额度,更新会员资金流水;
            \DB::transaction(function () use ($player,$gameAccount,$amount){
                $oldMainAccountAmount = $player->main_account_amount;
                $player->main_account_amount += $amount;
                $nowMainAccountAmount = $player->main_account_amount;
                $oldGameAccountAmount = $gameAccount->amount;
                $gameAccount->amount -= $amount;
                $nowGameAccountAmount = $gameAccount->amount;
                $player->carrier->remain_quota += $amount;
                $player->update();
                $gameAccount->update();
                $player->carrier->update();
                //新增会员资金流水记录
                $accountLog = new PlayerAccountLog();
                $accountLog->carrier_id = $player->carrier_id;
                $accountLog->remark = "主账户原余额： $oldMainAccountAmount 现余额： $nowMainAccountAmount 平台现余额： $nowGameAccountAmount";
                $accountLog->amount = $amount;
                //从游戏平台转到主账户
                $accountLog->fund_type = PlayerAccountLog::FUND_TYPE_TRANSFER;
                $accountLog->fund_source = $gameAccount->mainGamePlat->main_game_plat_name.'平台 转到 主账户';  //PlayerAccountLog::FUND_RESOURCE_GAME_ACCOUNT_TO_MAIN_ACCOUNT;
                $accountLog->player_id = $player->player_id;
                $accountLog->main_game_plat_id = $gameAccount->main_game_plat_id;
                $accountLog->save();
            });
        }catch (\Exception $e){
            throw  $e;
        }
    }


    /**
     * 更改PT游戏密码
     * @param Player $player
     * @param $password
     */
    public function updatePTPlayerPassword(Player $player, $password){
        if($this->game_gate_way instanceof PTGameGateway){
            $gameAccount = $this->getPlayerAccount($player);
            $this->game_gate_way->updatePlayerPassword($gameAccount,$password);
        }else{
            throw new GameGateWayRuntimeException('当前只有PT网关支持更改会员密码');
        }
    }


    /**
     * 将会员提线
     * @param Player $player
     * @throws \Exception
     */
    public function logout(Player $player){
        try{
            $gameAccount = $this->getPlayerAccount($player);
            $this->game_gate_way->logout($gameAccount);
        }catch (\Exception $e){
            throw $e;
        }
    }
}
