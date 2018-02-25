<?php namespace App\Entities;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/23
 * Time: 下午4:23
 */
class CacheConstantPrefixDefine
{

    /**
     *会员游戏账户信息缓存KEY前缀
     */
    const PLAYER_GAME_ACCOUNT_INFO_PREFIX = "PGAI_";

    /**
     *根据游戏英文名称查询的游戏信息缓存KEY前缀
     */
    const GAME_INFO_BY_GAME_ENGLISH_NAME_PREFIX = "GIBGENP_";

    /**
     *根据游戏英文名称查询的游戏信息缓存KEY前缀
     */
    const GAME_INFO_BY_GAME_TYPE_PREFIX = "GIBGTP_";

    /**
     *根据游戏代码查询的游戏信息缓存KEY前缀
     */
    const GAME_INFO_BY_GAME_CODE_PREFIX = "GIBGCP_";

    /**
     *根据运营商host获取运营商数据缓存KEY前缀
     */
    const SITE_URL_DOMAIN_CARRIER_INFO_CACHE_PREFIX  = "SUDCICP_";


    /**
     *运营商网站配置缓存Key前缀
     */
    const CARRIER_SITE_CONF_CACHE_PREFIX = "CSCCP_";

    /**
     *运营商网站banner配置缓存key前缀
     */
    const CARRIER_WEB_BANNER_CONF_CACHE_PREFIX = "CWBCCP_";


    /**
     *会员是否在线判断缓存KEY前缀
     */
    const MEMBER_USER_ONLINE_REMEMBER_CACHE_PREFIX = 'MUORCP_';


    /**
     *会员等级投注洗码配置
     */
    const CARRIER_PLAYER_LEVEL_REBATE_FINANCIAL_FLOW_CONFIGURE_CACHE_PREFIX = 'CPLRFFCCP_';


    /**
     *IP对应的地区数据信息
     */
    const IP_PLACE_CACHE_PREFIX = 'IPCP_';


    /**
     *运营商取款配置缓存Key前缀
     */
    const CARRIER_WITHDRAW_CONF_CACHE_PREFIX = 'CWCCP_';


    /**
     *运营商支付渠道缓存配置KEY前缀
     */
    const CARRIER_PAY_CHANNEL_CACHE_INFO_PREFIX = 'CPCCIP_';

    /**
     *根据代理商host获取代理网站数据缓存KEY前缀
     */
    const SITE_URL_DOMAIN_AGENT_INFO_CACHE_PREFIX  = "SUDAICP_";


    /**
     *运营商邀请好友配置缓存KEY前缀
     */
    const CARRIER_CACHED_INVITE_PLAYER_CONF = 'CCIPC_';


    /**
     *根据会员ID获取会员信息缓存KEY前缀
     */
    const PLAYER_INFO_CACHE_PREFIX = 'PICP_';

    /**
     *运营商所有会员等级信息缓存KEY前缀
     */
    const CARRIER_ALL_PLAYER_LEVEL_INFO_CACHE_PREFIX = 'CAPLICP_';


    /**
     *获取运营商信息缓存KEY前缀
     */
    const CARRIER_INFO_CACHE_PREFIX = 'CICP_';


    /**
     *运营商系统的所有缓存路由
     */
    const CARRIER_CACHED_ROUTE_LIST_PREFIX = 'CCRLP_';
}