<?php
namespace App\Models\Def;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarrierTemplate;

/**
 * App\Models\Def\PayChannel
 *
 * @property int $id
 * @property string $channel_name 银行卡名称 如 中国农业银行,微信
 * @property string $channel_code 编码
 * @property bool $pay_channel_type_id 银行类型
 *           1 传统银行 如:中国农业银行
 *           2 第三方支付 如:微信
 *           3 网络银行 如:网商银行
 * @property bool $is_need_private_key 是否需要填写私钥
 * @property bool $is_need_vir_card 是否需要填写转入账户
 * @property bool $is_need_domain 是否需要绑定域名
 * @property bool $is_need_good_name 是否需要绑定商品名称
 * @property string $icon_path_url 支付渠道图标
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\PayChannelType $payChannelType
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereChannelCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereChannelName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereIconPathUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereIsNeedMerchantCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereIsNeedPrivateKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel wherePayChannelTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannel whereUpdatedAt($value)
 *         @mixin \Eloquent
 */
class Template extends Model
{

    public $table = 'conf_templates';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'type',
        'value',
        'alias'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
        'value' => 'string',
        'alias' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function scopeInIds(Builder $query, $ids)
    {
        return $query->whereIn('id', $ids);
    }

    public function templates()
    {
        return $this->hasMany(CarrierTemplate::class, 'template_id', 'id');
    }
}
