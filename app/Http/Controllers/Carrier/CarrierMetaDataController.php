<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/27
 * Time: 下午6:05
 */

namespace App\Http\Controllers\Carrier;


use App\Http\Controllers\AppBaseController;
use App\Models\CarrierAgentUser;
use App\Models\CarrierPlayerLevel;
use App\Models\Player;
use Illuminate\Support\Facades\Route;

class CarrierMetaDataController extends AppBaseController
{

    public static function routeLists(){
        \Route::get('Carrier.allPlayerLevels','CarrierMetaDataController@allPlayerLevels')->name('Carrier.allPlayerLevels');
        \Route::get('Carrier.allAgents','CarrierMetaDataController@allAgents')->name('Carrier.allAgents');
        \Route::get('Carrier.allPlayers','CarrierMetaDataController@allPlayers')->name('Carrier.allPlayers');
    }
    /**
     * 获取当前运营商所有的会员等级
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPlayerLevels(){
        $level = CarrierPlayerLevel::active()->get();
        return self::sendResponse($level->toArray());
    }

    /**
     * 获取当前运营商所有的代理商
     * @return \Illuminate\Http\JsonResponse
     */
    public function allAgents(){
        $agent = CarrierAgentUser::active()->get(['id','realname','username']);
        return self::sendResponse($agent->toArray());
    }

    /**
     * 获取当前运营商所有的会员
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPlayers(){
        $players = Player::allPlayers();
        return self::sendResponse($players->toArray());
    }

}