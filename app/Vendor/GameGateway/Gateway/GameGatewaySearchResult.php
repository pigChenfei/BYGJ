<?php
/**
 * 游戏接口会员结果
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/10
 * Time: 下午5:06
 */

namespace App\Vendor\GameGateway\Gateway;


class GameGatewaySearchResult
{
    /**
     *会员投注结果赢
     */
    const PLAYER_BETTING_SUCCESS = 1;
    /**
     *会员投注结果输
     */
    const PLAYER_BETTING_FAILED  = -1;
    /**
     *会员投注结果平局
     */
    const PLAYER_BETTING_TIED    = 0;

    /**
     * 时间
     * @var
     */
    public $timestamp;

    /**
     * 金额
     * @var
     */
    public $amount;

}