<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/11
 * Time: 10:54
 */

namespace App\Vendor\GameGateway\Gateway;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;


abstract class GameGateway implements GameGatewayInterface
{


    public static function registerRoutes(){
        return [];
    }

    /**
     * 获取游戏平台账户
     * @param Player $user
     * @return PlayerGameAccount
     */
    public function getPlayerAccount(Player $user)
    {
        $playerGameAccount = PlayerGameAccount::byPlayerId($user->player_id)->byMainGameId($this->getMainGamePlat()->main_game_plat_id)->first();
        if($playerGameAccount){
            return $playerGameAccount;
        }
        $playerGameAccount = new PlayerGameAccount();
        $playerGameAccount->main_game_plat_id = $this->getMainGamePlat()->main_game_plat_id;
        $playerGameAccount->player_id = $user->player_id;
        $playerGameAccount->account_user_name = $user->gameAccountUserName();
        $playerGameAccount->save();
        return $playerGameAccount;
    }

    /**
     * 存款到游戏平台账户
     * @param PlayerGameAccount $gameAccount
     * @param float $amount
     * @return bool
     */
    function depositToPlayerGameAccount(PlayerGameAccount $gameAccount, $amount)
    {
        // 1, 判断该当前游戏平台账户是否可以存款
        try{
            $gameAccount->checkPlayerAccountCanDeposit($amount);
        }catch (\Exception $e){
            throw $e;
        }
    }

    function withdrawFromPlayerGameAccount(PlayerGameAccount $gameAccount, $amount)
    {
        try{
            $gameAccount->checkPlayerAccountCanWithDraw($amount);
        }catch (\Exception $e){
            throw $e;
        }
    }

}