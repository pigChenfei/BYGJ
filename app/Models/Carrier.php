<?php
namespace App\Models;

use App\Exceptions\AgentAccountException;
use App\Exceptions\CarrierAccountException;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Conf\CarrierDashLoginConf;
use App\Models\Conf\CarrierDepositConf;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Conf\CarrierThirdPartPay;
use App\Models\Conf\CarrierWebBannerConf;
use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Map\CarrierGamePlat;
use App\Models\CarrierTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Carrier
 *
 * @property int $id
 * @property string $name 运营商名称
 * @property string $site_url 站点地址
 * @property string $template 站点地址
 * @property bool $is_forbidden 是否禁用 1是 0否
 * @property bool $is_multi_agent 是否支持默认代理
 * @property mixed $remain_quota 当前额度
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\Conf\CarrierDashLoginConf $dashLoginConf
 * @property-read \App\Models\Conf\CarrierDepositConf $depositConf
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Map\CarrierGamePlat[] $mapGamePlats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarrierAgentUser[] $agents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conf\CarrierThirdPartPay[] $thirdPartPayConf
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conf\CarrierWebBannerConf[] $webBannerConf
 * @property-read \Illuminate\Database\Eloquent\Collection|CarrierPayChannel[] $carrierPayChannels
 * @property-read \App\Models\Conf\CarrierWebSiteConf $webSiteConf
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Carrier active()
 *         @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Carrier retrieveBySiteUrl($siteUrl)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Carrier authCarrier()
 */
class Carrier extends Model
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();
        self::updated(function (Carrier $carrier) {
            CarrierInfoCacheHelper::clearCarrierWebsiteConf($carrier);
            CarrierInfoCacheHelper::clearCachedCarrierInfoByCarrierId($carrier->id);
            CarrierInfoCacheHelper::clearCarrierInfoByHost($carrier->site_url);
            // 备用网站
            CarrierBackUpDomain::get([
                'domain'
            ])->where('carrier_id', $carrier->id)->each(function (CarrierBackUpDomain $domain) {
                CarrierInfoCacheHelper::clearCarrierInfoByHost($domain->domain);
            });
        });
    }

    /**
     * 运营商已禁用
     */
    const CARRIER_IN_FORBIDDEN = 1;

    /**
     * 运营商未禁用
     */
    const CARRIER_OUT_OF_FORBIDDEN = 0;

    public $table = 'inf_carrier';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'site_url',
        'is_forbidden',
        'remain_quota',
        'template',
        'template_agent',
        'template_agent_admin',
        'template_mobile',
        'is_multi_agent'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'site_url' => 'string',
        'remain_quota' => 'numeric',
        'is_forbidden' => 'boolean',
        'is_multi_agent' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_forbidden', self::CARRIER_OUT_OF_FORBIDDEN);
    }

    public function scopeRetrieveBySiteUrl(Builder $query, $siteUrl)
    {
        return $query->where('site_url', trim($siteUrl));
    }

    public function scopeAuthCarrier(Builder $query)
    {
        return $query->where('id', \WinwinAuth::carrierUser()->carrier_id);
    }

    /**
     *
     * @return bool
     */
    public function checkActive()
    {
        if ($this->is_forbidden == self::CARRIER_IN_FORBIDDEN) {
            throw new CarrierAccountException('运营商账户被禁用');
        }
        return true;
    }

    public function checkIsAllowUserLogin()
    {
        $this->checkActive();
        if ($this->dashLoginConf->is_allow_player_login == false) {
            throw new CarrierAccountException('运营商禁止会员登录');
        }
        return true;
    }

    public function checkIsAllowUserRegister()
    {
        $this->checkActive();
        if ($this->dashLoginConf->is_allow_player_register == false) {
            throw new CarrierAccountException('运营商禁止会员注册');
        }
        return true;
    }

    public function checkIsAllowAgentLogin()
    {
        $this->checkActive();
        if ($this->dashLoginConf->is_allow_agent_login == false) {
            throw new AgentAccountException('运营商禁止代理登录');
        }
        return true;
    }

    public function checkIsAllowAgentRegister()
    {
        $this->checkActive();
        if ($this->dashLoginConf->is_allow_agent_register == false) {
            throw new AgentAccountException('运营商禁止代理注册');
        }
        return true;
    }

    /**
     *
     * @return bool
     */
    public function isActive()
    {
        try {
            $this->checkActive();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function carrierUsers()
    {
        return $this->hasMany(CarrierUser::class, 'carrier_id', 'id');
    }

    public function mapGamePlats()
    {
        return $this->hasMany(CarrierGamePlat::class, 'carrier_id', 'id');
    }

    public function agents()
    {
        return $this->hasMany(CarrierAgentUser::class, 'carrier_id', 'id');
    }

    public function templates()
    {
        return $this->hasMany(CarrierTemplate::class, 'carrier_id', 'id');
    }

    public function carrierPayChannels()
    {
        return $this->hasMany(CarrierPayChannel::class, 'carrier_id', 'id');
    }

    public function carrierBackUpDomain()
    {
        return $this->hasMany(CarrierBackUpDomain::class, 'carrier_id', 'id');
    }

    /**
     * 第三方支付配置项
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thirdPartPayConf()
    {
        return $this->hasMany(CarrierThirdPartPay::class, 'carrier_id', 'id');
    }

    /**
     * 后台登录配置项
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dashLoginConf()
    {
        return $this->hasOne(CarrierDashLoginConf::class, 'carrier_id', 'id');
    }

    /**
     * web banner配置项
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webBannerConf()
    {
        return $this->hasMany(CarrierWebBannerConf::class, 'carrier_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function webSiteConf()
    {
        return $this->hasOne(CarrierWebSiteConf::class, 'carrier_id', 'id');
    }

    /**
     * 存款设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function depositConf()
    {
        return $this->hasOne(CarrierDepositConf::class, 'carrier_id', 'id');
    }

    /**
     * 邀请好友设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invitePlayerConf()
    {
        return $this->hasOne(CarrierInvitePlayerConf::class, 'carrier_id', 'id');
    }

    public function carrierPins()
    {
        return $this->hasMany(CarrierPin::class, 'carrier_id', 'id');
    }

    public function serviceTeams()
    {
        return $this->hasMany(CarrierServiceTeam::class, 'carrier_id', 'id');
    }

    public function allPlayerLevels()
    {
        return $this->hasMany(CarrierPlayerLevel::class, 'carrier_id', 'id');
    }

    public function checkRemainQuotaEnough($takenQuota = 0.0)
    {
        if ($this->remain_quota - $takenQuota < 0) {
            throw new CarrierAccountException('运营商额度不足');
        }
    }

    /**
     * 运营商额度是否足够
     *
     * @param float $takenQuota
     *            减去某个额度后
     * @return bool
     */
    public function isRemainQuotaEnough($takenQuota = 0.0)
    {
        try {
            $this->checkRemainQuotaEnough($takenQuota);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
