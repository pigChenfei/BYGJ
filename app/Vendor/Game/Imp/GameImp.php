<?php
namespace App\Vendor\Game\Imp;

use App\Models\PlayerTransfer;
use App\Models\PlayerGameAccount;

interface GameImp
{

    /**
     * 自动查账
     *
     * @param PlayerTransfer $playTransfer
     * @param PlayerGameAccount $account
     */
    public function checkTransfer(PlayerTransfer $playTransfer, PlayerGameAccount $account);

    /**
     * 用不登录
     *
     * @param unknown $playerName
     * @param unknown $channelId
     */
    public function login($playerName, $channelId = NULL);

    /**
     * 转入游戏账户
     *
     * @param unknown $accountUserName
     * @param unknown $money
     */
    public function transferIn($accountUserName, $money);

    /**
     * 转出游戏账户
     *
     * @param unknown $accountUserName
     * @param unknown $money
     */
    public function transferOut($accountUserName, $money);
}

