<?php
namespace App\Models;

use App\Helpers\Caches\AgentInfoCacheHelper;
use Eloquent as Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CarrierAgentDomain
 *
 * @package App\Models
 * @version March 6, 2017, 3:18 am UTC
 * @property int $id
 * @property int $agent_id 代理ID
 * @property int $carrier_id 运营商ID
 * @property string $website 邀请域名
 * @property \Carbon\Carbon $created_at 添加时间
 * @property \Carbon\Carbon $updated_at 修改时间
 * @property-read \App\Models\CarrierAgentUser $agent
 * @property-read \App\Models\Carrier $carrier @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierAgentDomain byWebsite($siteUrl)
 */
class CarrierAgentDomain extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        self::created(function (CarrierAgentDomain $domain) {
            AgentInfoCacheHelper::clearAgentInfoByDomain($domain);
        });
        self::updated(function (CarrierAgentDomain $domain) {
            AgentInfoCacheHelper::clearAgentInfoByDomain($domain);
        });
    }

    public $table = 'inf_agent_domain';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'agent_id',
        'carrier_id',
        'website'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'carrier_id' => 'integer',
        'website' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'website' => 'required|max:255'
    ];

    public static $requestAttributes = [
        'website' => '代理域名'
    ];

    public static function createRules($current_carrier_id)
    {
        return self::$rules;
        // return array_merge(self::$rules,[
        // 'website' => 'required|max:255|unique:inf_agent_domain,website,NULL,id,carrier_id,'.$current_carrier_id,
        // ]);
    }

    public static function updateRules($current_carrier_id, $except_id)
    {
        return self::$rules;
        // return array_merge(self::$rules,[
        // 'website' => 'required|max:255|unique:inf_agent_domain,website,'.$except_id.',id,carrier_id,'.$current_carrier_id,
        // ]);
    }

    /**
     * 获取代理用户信息
     *
     * @return type
     */
    public function agent()
    {
        return $this->hasOne(CarrierAgentUser::class, 'id', 'agent_id');
    }

    public function scopeByWebsite(Builder $query, $siteUrl)
    {
        return $query->where('website', $siteUrl);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }
}
