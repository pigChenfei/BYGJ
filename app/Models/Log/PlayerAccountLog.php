<?php

namespace App\Models\Log;

use App\Models\CarrierUser;
use App\Models\Def\MainGamePlat;
use App\Models\Player;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlayerAccountLog
 *
 * @package App\Models
 * @version March 8, 2017, 3:49 am UTC
 * @property int $log_id
 * @property int $player_id 会员ID
 * @property int $carrier_id
 * @property int $main_game_plat_id 游戏主平台id
 * @property float $amount 操作金额
 * @property \Carbon\Carbon $created_at
 * @property int $fund_type 资金类型  1 存款 2 取款 3 红利 4 洗码 5转账
 * 1：存款
 * 2：取款
 * 3：红利
 * 4：洗码
 * 5：转账
 * @property string $fund_source 流水来源 例如: 从会员主账户转出到游戏
 * @property string $remark 备注
 * @property int $operator_reviewer_id 运营商审核的客服id
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\MainGamePlat $mainGamePlat
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\CarrierUser $serviceUser
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerAccountLog byFinishTimeRange($startTime, $endTime)
 */
class PlayerAccountLog extends Model
{

    use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
    }

    //存款
    const FUND_TYPE_DEPOSIT = 1;
    //存款优惠
    const FUND_TYPE_DEPOSIT_BENEFIT = 7;
    //取款 存款记录只是记录简单的的存款, 详细存款记录参考PlayerDepositPayLog, 记录了存款优惠 手续费等等信息. 统计信息也建议从PlayerDepositPayLog表统计,不要从该表统计信息.
    const FUND_TYPE_WITHDRAW = 2;
    //红利
    const FUND_TYPE_BONUS = 3;
    //洗码
    const FUND_TYPE_FINANCIAL_FLOW = 4;
    //转账
    const FUND_TYPE_TRANSFER = 5;
    //好友邀请奖励
    const FUND_TYPE_INVITED_PLAYER_REWARD = 6;

    public $table = 'log_player_account';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'log_id';

    public $fillable = [
        'player_id',
        'carrier_id',
        'main_game_plat_id',
        'amount',
        'fund_type',
        'fund_source',
        'remark',
        'operator_reviewer_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'log_id' => 'integer',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'main_game_plat_id' => 'integer',
        'fund_source' => 'string',
        'remark' => 'string',
        'amount' => 'float',
        'operator_reviewer_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static function fundTypeMeta(){
        return [
            self::FUND_TYPE_DEPOSIT => '存款',
            self::FUND_TYPE_WITHDRAW => '取款',
            self::FUND_TYPE_BONUS => '红利',
            self::FUND_TYPE_TRANSFER => '转账',
            self::FUND_TYPE_FINANCIAL_FLOW => '洗码',
            self::FUND_TYPE_INVITED_PLAYER_REWARD => '好友邀请奖励',
            self::FUND_TYPE_DEPOSIT_BENEFIT => '存款优惠',
        ];
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

    public function mainGamePlat(){
        return $this->hasOne(MainGamePlat::class,'main_game_plat_id','main_game_plat_id');
    }

    public function scopeByFinishTimeRange(Builder $query, $startTime, $endTime){
        return $query->whereBetween('created_at',[$startTime,$endTime]);
    }

    public function scopeOrderByCreatedTime(Builder $query,$type){
        return $query->orderBy('created_at',$type);
    }

    /**
     * 操作人员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceUser(){
        return $this->belongsTo(CarrierUser::class,'operator_reviewer_id','id');
    }



}
