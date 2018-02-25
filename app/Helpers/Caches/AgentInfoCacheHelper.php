<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/15
 * Time: 下午10:50
 */

namespace App\Helpers\Caches;


use App\Entities\CacheConstantPrefixDefine;
use App\Models\CarrierAgentDomain;
use App\Models\CarrierAgentUser;

class AgentInfoCacheHelper
{


    /**
     * @param $siteUrl
     * @return CarrierAgentUser;
     */
    public static function getAgentInfoByDomain($siteUrl){
        return \Cache::remember(CacheConstantPrefixDefine::SITE_URL_DOMAIN_AGENT_INFO_CACHE_PREFIX.$siteUrl,1440,function () use ($siteUrl){
            $info =  CarrierAgentDomain::byWebsite($siteUrl)->first();
            if($info){
                return $info->agent;
            }
            return null;
        });
    }

    /**
     * @param CarrierAgentDomain $agentDomain
     */
    public static function clearAgentInfoByDomain(CarrierAgentDomain $agentDomain){
        \Cache::forget(CacheConstantPrefixDefine::SITE_URL_DOMAIN_AGENT_INFO_CACHE_PREFIX.$agentDomain->website);
    }
}