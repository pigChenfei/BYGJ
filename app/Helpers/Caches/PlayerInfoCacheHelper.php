<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/26
 * Time: 下午10:16
 */

namespace App\Helpers\Caches;


use App\Entities\CacheConstantPrefixDefine;
use App\Models\Player;

class PlayerInfoCacheHelper
{


    /**
     *
     * @param $playerId
     * @return Player
     */
    public static function getPlayerCacheInfoById($playerId){
        return \Cache::remember(CacheConstantPrefixDefine::PLAYER_INFO_CACHE_PREFIX.$playerId,1440,function () use ($playerId){
            return Player::findOrFail($playerId);
        });
    }

    public static function clearPlayerCacheInfoById($playerId){
        \Cache::forget(CacheConstantPrefixDefine::PLAYER_INFO_CACHE_PREFIX.$playerId);
    }

}