<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/8
 * Time: 下午10:29
 */

namespace App\Vendor\GameGateway\Gateway\Exception;


use App\Vendor\GameGateway\Gateway\GameGatewayInterface;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;

class GameGateWaySynchronizeDBException extends \RuntimeException
{

    /**
     * @var GameGatewaySearchCondition
     */
    public $searchCondition;


    /**
     * @var GameGatewayInterface
     */
    public $gameGateWay;


}