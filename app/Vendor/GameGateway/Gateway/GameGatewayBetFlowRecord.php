<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/23
 * Time: 下午2:00
 */
namespace App\Vendor\GameGateway\Gateway;

class GameGatewayBetFlowRecord
{

    public $playerBetFlowDBId;

    // 会员账号
    public $playerName;

    // 游戏类型
    public $gameType;

    // 庄闲投注 0 无, 1庄 2闲 3庄闲都投注
    public $playerOrBanker = 0;

    // 游戏代码名称
    public $gameCode;

    // 游戏名称 全称
    public $gameName;

    // 游戏流水号
    public $code;

    // 是否投注有效
    public $isBetAvailable;

    // 有效投注额
    public $availableBet = 0;

    // 投注额
    public $bet;

    // 当前投注额
    public $currentBet;

    // 累计奖池投注额
    public $progressiveBet;

    // 累计奖池派彩
    public $progressiveWin;

    // 派彩
    public $win;

    // 余额
    public $balance;

    // 时间
    public $date;

    // 投注内容
    public $betInfo;
}