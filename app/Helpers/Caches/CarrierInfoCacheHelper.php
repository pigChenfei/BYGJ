<?php
namespace App\Helpers\Caches;

use App\Entities\CacheConstantPrefixDefine;
use App\Models\Carrier;
use App\Models\CarrierAgentDomain;
use App\Models\CarrierBackUpDomain;
use App\Models\CarrierPayChannel;
use App\Models\CarrierPlayerLevel;
use App\Models\Conf\CarrierInvitePlayerConf;
use Illuminate\Database\Eloquent\Collection;
use App\Models\CarrierAgentUser;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/15
 * Time: 下午1:29
 */
class CarrierInfoCacheHelper
{

    /**
     * 根据运营商id获取运营商信息
     *
     * @param $carrierId
     * @return Carrier
     */
    public static function getCachedCarrierInfoByCarrierId($carrierId)
    {
        return \Cache::remember(CacheConstantPrefixDefine::CARRIER_INFO_CACHE_PREFIX . $carrierId, 1440,
            function () use ($carrierId) {
                return Carrier::findOrFail($carrierId);
            });
    }

    /**
     *
     * @param $carrierId
     */
    public static function clearCachedCarrierInfoByCarrierId($carrierId)
    {
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_INFO_CACHE_PREFIX . $carrierId);
    }

    /**
     * 获取运营商所有支付渠道信息
     *
     * @return CarrierPayChannel[]|Collection
     */
    public static function getAllCachedCarrierPayChannels()
    {
        return \Cache::remember(
            CacheConstantPrefixDefine::CARRIER_PAY_CHANNEL_CACHE_INFO_PREFIX . \WinwinAuth::carrierUser()->carrier_id,
            1440, function () {
                return CarrierPayChannel::with('payChannel')->get();
            });
    }

    /**
     * 清除运营商支付渠道缓存数据
     */
    public static function clearAllCachedCarrierPayChannels()
    {
        global $nowCarrierId;
        if (\WinwinAuth::carrierUser()) {
            $nowCarrierId = \WinwinAuth::carrierUser()->carrier_id;
        }
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_PAY_CHANNEL_CACHE_INFO_PREFIX . $nowCarrierId);
    }

    /**
     * 清除运营商网站配置缓存数据
     *
     * @param Carrier $carrier
     */
    public static function clearCarrierWebsiteConf(Carrier $carrier)
    {
        return \Cache::forget(CacheConstantPrefixDefine::CARRIER_SITE_CONF_CACHE_PREFIX . $carrier->id);
    }

    /**
     * 获取玩家邀请设置信息
     *
     * @param Carrier $carrier
     * @return CarrierInvitePlayerConf
     */
    public static function getCachedInvitePlayerConf(Carrier $carrier)
    {
        return \Cache::remember(CacheConstantPrefixDefine::CARRIER_CACHED_INVITE_PLAYER_CONF . $carrier->id, 1440,
            function () use ($carrier) {
                return $carrier->invitePlayerConf;
            });
    }

    /**
     *
     * @param Carrier $carrier
     */
    public static function clearCachedInvitePlayerConf(Carrier $carrier)
    {
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_CACHED_INVITE_PLAYER_CONF . $carrier->id);
    }

    /**
     * 获取所有有效的玩家等级列表
     *
     * @param Carrier $carrier
     * @return CarrierPlayerLevel[]|Collection
     */
    public static function getCachedAllActivePlayerLevelInfo(Carrier $carrier)
    {
        return \Cache::remember(CacheConstantPrefixDefine::CARRIER_ALL_PLAYER_LEVEL_INFO_CACHE_PREFIX . $carrier->id,
            1440,
            function () use ($carrier) {
                $carrier->load(
                    [
                        'allPlayerLevels' => function ($query) {
                            return $query->active()
                                ->bySort('asc');
                        }
                    ]);
                return $carrier->allPlayerLevels;
            });
    }

    /**
     *
     * @param Carrier $carrier
     */
    public static function clearCachedAllActivePlayerLevelInfo(Carrier $carrier)
    {
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_ALL_PLAYER_LEVEL_INFO_CACHE_PREFIX . $carrier->id);
    }

    /**
     *
     * @param $host
     * @return Carrier
     */
    public static function getCachedCarrierInfoByHost($host)
    {
        return \Cache::remember(CacheConstantPrefixDefine::SITE_URL_DOMAIN_CARRIER_INFO_CACHE_PREFIX . $host, 1440,
            function () use ($host) {
                $agent = \Session::get('currentAgentUser');
                if (! $agent) {
                    $ak = app('request')->get('ak', '');
                    if (! empty($ak)) {
                        $carrier = Carrier::retrieveBySiteUrl($host)->first();
                        if (! $carrier) {
                            // 如果找不到, 则从备用域名中检测
                            $carrierDomain = CarrierBackUpDomain::retrieveByDomainName($host)->first();
                            if ($carrierDomain) {
                                $carrier = $carrierDomain->carrier;
                            }
                        }
                        if (! is_null($carrier)) {
                            $agent = CarrierAgentUser::where('promotion_code', $ak)->where('carrier_id', $carrier->id)->first();
                            session('currentAgentUser', $agent);
                        }
                    }
                }
                // 先从代理找域名
                if (empty($agent)) {
                    $agentDomain = CarrierAgentDomain::byWebsite($host)->first();
                    $agent = is_null($agentDomain) ? null : $agentDomain->agent;
                }
                
                if (is_null($agent)) {
                    $carrier = Carrier::retrieveBySiteUrl($host)->first();
                    if (! $carrier) {
                        // 如果找不到, 则从备用域名中检测
                        $carrierDomain = CarrierBackUpDomain::retrieveByDomainName($host)->first();
                        if ($carrierDomain) {
                            $carrier = $carrierDomain->carrier;
                        }
                    }
                } else {
                    $carrier = $agent->carrier;
                }
                return compact('agent', 'carrier');
            });
    }

    /**
     *
     * @param $host
     */
    public static function clearCarrierInfoByHost($host)
    {
        \Cache::forget(CacheConstantPrefixDefine::SITE_URL_DOMAIN_CARRIER_INFO_CACHE_PREFIX . $host);
    }
}