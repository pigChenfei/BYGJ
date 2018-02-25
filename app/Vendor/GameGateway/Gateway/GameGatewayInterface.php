<?php
namespace App\Vendor\GameGateway\Gateway;
use App\Models\Def\MainGamePlat;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\PT\PTGamePlayerBalance;
use App\Vendor\GameGateway\Query\QueryResult;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/10
 * Time: 下午4:37
 */
interface GameGatewayInterface
{


    /**
     * 注册的路由
     * @return mixed
     */
    public static function registerRoutes();

    /**主游戏平台代码
     * @return string
     */
    public static function getMainGamePlatCode();

    /**
     * 主游戏平台
     * @return MainGamePlat;
     */
    public function getMainGamePlat();

    /**
     * 获取登录游戏页面page内容,用于填充登录跳转页,此方法不是登录
     * @param PlayerGameAccount $gameAccount
     * @param string $gameCode 登录的游戏代码
     * @return GameGatewayLoginEntity
     */
    public function loginPageEntity(PlayerGameAccount $gameAccount, $gameCode);

    /**
     * 退出登录游戏 可以自己退出 也可以强制踢出
     * @param PlayerGameAccount $gameAccount 会员用户账户
     * @return bool
     */
    public function logout(PlayerGameAccount $gameAccount);

    /**
     *获取会员游戏账户,需要判断是否已经存在,如果已经存在会员账户则不需要创建
     * @param Player $user 会员
     * @return PlayerGameAccount 会员用户账户
     */
    public function getPlayerAccount(Player $user);

    /**
     * 从主账户存款到游戏平台账户
     * @param PlayerGameAccount $gameAccount 会员用户账户
     * @param $amount float 金额
     * @return QueryResult
     */
    public function depositToPlayerGameAccount(PlayerGameAccount $gameAccount, $amount);

    /**
     * 从游戏平台账户转帐到主账户
     * @param PlayerGameAccount $gameAccount 会员用户账户
     * @param $amount float 金额
     * @return bool 成功还是失败
     */
    public function withdrawFromPlayerGameAccount(PlayerGameAccount $gameAccount, $amount);


    /**
     * 获取游戏投注流水记录
     * @param GameGatewaySearchCondition $condition
     * @param PlayerGameAccount|null $gameAccount 如果参数为空, 则是所有会员的投注流水记录
     * @return GameGatewayBetFlowRecord[]
     */
    public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null);

    /**
     * 同步会员游戏投注流水数据
     * @return GameGatewayBetFlowRecord[]
     */
    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition, PlayerGameAccount $gameAccount = null);


    /**
     * 最后一次同步游戏投注的时间戳
     * @return int
     */
    public function lastSynchronizedGameFlowTimeStamp();


    /**
     * 更新同步时间
     * @param string $time Y-m-d H:i:s 格式
     * @return mixed
     */
    public function updateSynchronizedGameFlowTimeStamp($time);


    /**
     * 更新游戏端会员姓名
     * @param PlayerGameAccount $gameAccount
     * @param string $userName
     * @return bool
     */
    //public function updatePlayerUsername(PlayerGameAccount $gameAccount,$userName);

    /**
     * 会员是否在线
     * @param PlayerGameAccount $gameAccount
     * @return bool
     */
    public function isPlayerOnline(PlayerGameAccount $gameAccount);

    /**
     * 同步会员账户信息至双赢后台
     * @param PlayerGameAccount $gameAccount
     * @return mixed
     */
    public function synchronizePlayerAccountInfo(PlayerGameAccount &$gameAccount);


    /**
     * 解除会员失败登录锁定记录
     * @param PlayerGameAccount $gameAccount
     * @return bool
     */
    public function resetLoginFailedAttempts(PlayerGameAccount $gameAccount);


    /**
     * 获取平台会员列表
     * @return [PlayerGameAccount] 会员列表
     */
    public function playerList();

}