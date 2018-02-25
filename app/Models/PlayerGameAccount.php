<?php

namespace App\Models;

use App\Entities\CacheConstantPrefixDefine;
use App\Exceptions\PlayerAccountException;
use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Scopes\PlayerScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



/**
 * App\Models\PlayerGameAccount
 *
 * @property int $account_id
 * @property int $main_game_plat_id 对应的游戏平台id
 * @property int $player_id 会员ID
 * @property string $account_user_name 账户账号  各平台账号账号不一样  用于注册游戏平台使用
 * @property float $amount 账户余额
 * @property bool $is_need_repair 是否需要开启维护 如果开启维护, 那么用户不能登录游戏
 * @property bool $is_locked 账号是否锁定 1锁定 0 未锁定
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at
 * @property string $extra_field 其他自定义数据,  根据不同的游戏平台商的策略的自定义数据. json格式
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount byMainGameId($mainGameId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount byPlayerId($playerId)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount retrieveByAccountUserName($accountUserName)
 * @property-read \App\Models\Def\MainGamePlat $mainGamePlat
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount byMainGamePlatIdNotIn($notInIds)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereAccountUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereExtraField($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereIsLocked($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereIsNeedRepair($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereMainGamePlatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount wherePlayerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerGameAccount whereUpdatedAt($value)
 */
class PlayerGameAccount extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new PlayerScope());
    }

    public $table = 'inf_player_game_account';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'account_id';

    public $fillable = [
        'main_game_plat_id',
        'player_id',
        'amount',
        'is_locked',
        'account_user_name',
        'extra_field',
        'is_need_repair'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'account_id' => 'integer',
        'main_game_plat_id' => 'integer',
        'player_id' => 'integer',
        'amount'    => 'numeric',
        'is_locked' => 'boolean',
        'is_need_repair' => 'boolean'
    ];

    public function getAmountAttribute($value = null){
        return isset($value) ? floatval($value) : 0.0;
    }


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function scopeByPlayerId(Builder $query,$playerId){
        return $query->where('player_id',$playerId);
    }

    public function scopeByMainGameId(Builder $query,$mainGameId){
        return $query->where('main_game_plat_id',$mainGameId);
    }

    public function scopeByMainGamePlatIdNotIn(Builder $query,$notInIds){
        return $query->whereNotIn('main_game_plat_id',$notInIds);
    }

    public function scopeRetrieveByAccountUserName(Builder $query,$accountUserName){
        return $query->where('account_user_name',$accountUserName);
    }

    /**
     * 根据会员游戏账户名获取缓存游戏账户
     * @param $playerAccountName
     * @return PlayerGameAccount
     */
    public static function getCachedPlayerGameAccountByPlayerName($playerAccountName){
        return \Cache::remember(CacheConstantPrefixDefine::PLAYER_GAME_ACCOUNT_INFO_PREFIX.$playerAccountName,3600,function () use ($playerAccountName){
            $playerGameAccount = PlayerGameAccount::retrieveByAccountUserName($playerAccountName)->with(['player.playerLevel','player.agent'])->first();
            return $playerGameAccount;
        });
    }


    /**
     * 当前游戏账户是否在限游戏流水平台列表
     * @return bool
     */
    public function isInFlowLimitGamePlats(){
        // 1, 获取最早有参与活动的且有限游戏平台的流水限平台记录;
        $playerBetFlowLimitLog  = PlayerWithdrawFlowLimitLog::has('limitGamePlats','>=','1')->byPlayerId($this->player_id)->with('limitGamePlats')->earliestUnfinishedLog()->first();
        if($playerBetFlowLimitLog && $gamePlats = $playerBetFlowLimitLog->limitGamePlats){
            $gamePlatsId = $gamePlats->each(function(PlayerWithdrawFlowLimitLogGamePlat $element){
                return $element->def_game_plat_id;
            })->toArray();
            if(in_array($this->main_game_plat_id,$gamePlatsId)){
                return true;
            }
        }
        return false;
    }

    /**
     * 是否可以存款到游戏账户
     * @param float $amount
     * @return bool
     * @throws \Exception
     */
    public function checkPlayerAccountCanDeposit($amount = 0.0){
        try{
            //1,判断当前会员游戏账户是否被锁定
            if($this->is_locked == true){
                throw new PlayerAccountException('会员账户被锁定');
            }
            //2,判断当前账户是否在限流水游戏平台, 否则不能转账
            if($this->isInFlowLimitGamePlats()){
                throw new PlayerAccountException('该游戏平台账户在限流水游戏平台列表中,暂时不能转账.请完成相应流水!');
            }
            //3,判断会员账号状态是否正常
            $this->player->checkLocked();
            //4,判断主账户余额是否足够
            if($this->player->isRemainAccountEnough($amount) == false){
                throw new PlayerAccountException('会员主账户余额不足');
            }
            //5,判断运营商额度是否足够;
            if($this->player->carrier->isRemainQuotaEnough($amount) == false){
                throw new PlayerAccountException('运营商额度不足');
            }
            return true;
        }catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * 是否可以从游戏账户取款
     * @return bool
     * @throws \Exception
     */
    public function checkPlayerAccountCanWithDraw($amount){
        try{
            //1,判断当前会员游戏账户是否被锁定
            if($this->is_locked == true){
                throw new PlayerAccountException('会员账户被锁定');
            }
            //2,判断当前账户是否在限流水游戏平台, 否则不能转账
            if($this->isInFlowLimitGamePlats()){
                throw new PlayerAccountException('该游戏平台账户在限流水游戏平台列表中,暂时不能转账.请完成相应流水!');
            }
            //3,判断游戏平台余额是否足够
            /*if($this->amount < $amount){
                throw new PlayerAccountException('游戏账户余额不足');
            }*/
            //4,判断会员账号状态是否正常
            $this->player->checkLocked();
            return true;
        }catch (\Exception $e){
            throw $e;
        }
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

    public function mainGamePlat(){
        return $this->belongsTo(MainGamePlat::class,'main_game_plat_id','main_game_plat_id');
    }

    /**
     * 生成随机字符串
     */
    public static function generateValue($length = 8, $pre = '', $carrier_id = '0')
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY0123456789';
        $value = '';
        for ($i = 0; $i < $length; $i ++) {
            $value .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $value = $pre.$carrier_id.'Z'.$value;
        $info = self::retrieveByAccountUserName($value)->first();
        if ($info){
            return self::generateValue($length,$pre, $carrier_id);
        }
        return $value;
    }
}
