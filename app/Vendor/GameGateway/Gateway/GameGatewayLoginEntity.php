<?php

namespace App\Vendor\GameGateway\Gateway;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/17
 * Time: 上午10:04
 */
class GameGatewayLoginEntity
{


    /**
     * @var
     */
    public $scripts;

    /**
     * @var
     */
    public $dom;

    /**
     * @var
     */
    public $css;


    public $gameCode;

    /**
     * @var \App\Models\PlayerGameAccount;
     */
    public $gameAccount;

}